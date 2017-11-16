<?php
/**
 * REST Full server for Codeigniter 3
 * 
 * @author Yoann Vanitou <yvanitou@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link   https://github.com/maltyxx/restserver
 * @since   Version 2.1.0
 * @filesource
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require(__DIR__.'/Restserver_field.php');
require(__DIR__.'/Restserver_rule.php');

/** 
 * Restserver (Librairie REST Serveur)
 */
class Restserver
{
    /**
     * Instance de Codeigniter
     * @var object $CI
     */
    protected $CI;
    
    /**
     * Configuration
     * @var array
     */
    protected $config = array(
        'allow_methods'     => array('GET', 'POST', 'PUT', 'DELETE'),
        'allow_headers'     => array('X-RestServer'),
        'allow_credentials' => FALSE,
        'allow_origin'      => FALSE,
        'force_https'       => FALSE,
        'ajax_only'         => FALSE,
        'auth_http'         => FALSE,
        'cache'             => FALSE,
        'debug'             => FALSE,
        'log'               => FALSE,
        'log_driver'        => 'file',
        'log_db_name'       => 'rest', // Database only
        'log_db_table'      => 'log', // Database only
        'log_file_path'     => '', // File only
        'log_file_name'     => 'rest.log', // File only
        'log_extra'         => FALSE
    );

    /**
     * Protocole
     * @var array
     */
    protected $protocol;

    /**
     * Instance du controlleur
     * @var CI_Controller
     */
    protected $controller;

    /**
     * La méthode
     * @var string
     */
    protected $method;

    /**
     * L'URL
     * @var string
     */
    protected $url;

    /**
     * L'IP
     * @var string
     */
    protected $ip;

    /**
     * L'identifiant
     * @var integer
     */
    protected $auth;

    /**
     * Les en-têtes
     * @var string
     */
    protected $headers;

    /**
     * Les entrées
     * @var array
     */
    protected $input;

    /**
     * Les sorties
     * @var string
     */
    protected $output;

    /**
     * Le temps d'exécution
     * @var string
     */
    protected $exectime;

    /**
     * Configuration des champs
     * @var array
     */
    private $fields = array();

    /**
     * Les données entrantes
     * @var array
     */
    protected $field_input = array();

    /**
     * Les alias
     * @var type 
     */
    protected $alias = array();

    /**
     * Constructeur
     * @param array $config
     */
    function __construct(array $config = array())
    {
        // Charge l'instance de CodeIgniter
        $this->CI =& get_instance();
        
        $package_path = FCPATH.'vendor/maltyxx/restserver';

        // Change les paquets        
        $this->CI->load->add_package_path($package_path)
            ->library('form_validation')
            ->helper('restserver')
            ->remove_package_path($package_path);
        
        $this->CI->load->helper(array(
            'url'
        ));
                
        // Verifie si le /third_party/restserver/libraries/MY_Form_validation.php est chargé
        if ( ! method_exists($this->CI->form_validation, 'required_post')) {
            exit("Can not load MY_Form_validation.php");
        }
        
        // Initialise la configuration
        $this->initialize($config);
    }
    
    /**
     * Initialisation
     * @param array $config
     */
    public function initialize(array $config)
    {
        // Si il y a une arborescence
        if (isset($config['restserver'])) {
            $config = $config['restserver'];
        }
        
        // Merge la configuration
        $this->config = array_merge($this->config, $config);

        // Si le journal est activé
        if ($this->config['log']) {
            $this->CI->benchmark->mark('restserver_start');
        }
        
        // Récupération du protocol
        $this->protocol = restserver_protocol();
    }

    /**
     * Exécute la routine
     * @param Restserver_Controller $controller
     * @param string $call
     * @param array $params
     * @return boolean
     */
    public function run(Restserver_Controller &$controller, $call, $params)
    {
        // Collecte les données
        $this->controller = & $controller;
        $this->method     = $this->_get_method();
        $this->url        = $this->_get_url();
        $this->ip         = $this->_get_ip();
        $this->headers    = $this->_get_headers();
        $this->input      = $this->_get_input();

        // Envoi les autorisations pour le cross-domain
        $this->_cross_domain();

        // Si la requête est de type options (cross domain)
        if ($this->method === 'options') {
            $this->response(array(
                'status' => TRUE
            ), 200);

            return TRUE;
        }

        // Si le protocole SSL est obligatoire
        if ($this->config['force_https'] && ! $this->_is_sslprotocol()) {
			$this->response(array(
                'status' => FALSE,
                'error' => 'Unsupported protocol'
            ), 403);

            return FALSE;
		}

        // Si la requête est en ajax
        if ($this->config['ajax_only'] && ! $this->CI->input->is_ajax_request()) {
			$this->response(array(
                'status' => FALSE,
                'error' => 'Only AJAX requests are accepted'
            ), 505);

            return FALSE;
		}

        // Authentification
        if ($this->config['auth_http']) {
            if (!$this->auth = $this->_authentication()) {
                return FALSE;
            }
        }

        // Si la méthode existe
        if ( ! method_exists($this->controller, $this->method)) {
            $this->response(array(
                'status' => FALSE,
                'error' => 'Method not found'
            ), 405);

            return FALSE;
        }

        // Si la documentation est demandé
        if (isset($this->input['get']['help'])) {
            // Récupère les fields pour la documentation
            $docs = $this->_get_docs();

            // Si il existe une docuementation
            if ( ! empty($docs)) {
                $this->response(array(
                    'status' => TRUE,
                    'value' => $docs
                ), 200);

                return TRUE;
            }
        }

        // Si la documentation HAR est demandée (http://www.softwareishard.com/blog/har-12-spec/#request)
        if (isset($this->input['get']['har']) && $har = $this->input['get']['har']) {
            // Récupère les fields pour la documentation
            $doc = $this->_get_doc_har($har);

            // Si il existe une docuementation
            if (!empty($doc)) {
                $this->response($doc, 200);

                return TRUE;
            }
        }

        // Récupère les règles
        $rules = $this->_get_rules();

        // Si des règles existent
        if ( ! empty($rules)) {
            // Vérification des données entrantes
            $this->CI->form_validation->set_data($this->input[$this->method]);
            $this->CI->form_validation->set_rules($rules);
            $this->CI->form_validation->set_error_delimiters('', '');
            
            // Si le validateur a rencontré une ou plusieurs erreurs
            if ($this->CI->form_validation->run() === FALSE) {
                $errors = $this->CI->form_validation->error_array();
                
                $this->response(array(
                    'status' => FALSE,
                    'error'  => (!empty($errors)) ? $errors : 'Unsupported data validation'
                ), 400);

                return FALSE;
            }
        }

        // Création des input
        $this->field_input = $this->_get_field_input();

        // Création des alias
        $this->alias = $this->_get_alias();

        // Exécute la méthode
        call_user_func_array(array($this->controller, $this->method), $params);

        return TRUE;
    }

    /**
     * Déclaration de la configuration d'un champ
     * @param Restserver_field|array $field
     * @deprecated since 2.1.0
     */
    public function add_field($field)
    {
        // Si le data est un tableau de champ
        if (is_array($field)) {
            foreach ($field as $value) {
                $this->add_field($value);
            }
        // Si le data est une instance
        } else if ($field instanceof Restserver_field) {
            $this->fields[] = $field;
        }
    }
    
    /**
     * Configuration des règles
     * @param string|array $field
     * @param string $label
     * @param string|array $rules
     * @param string $errors
     * @return Restserver
     */
    public function set_rules($field)
    {
        // Si la configuration est un tableau
        if (is_array($field)) {
            foreach ($field as $config) {
                $this->fields[] = new Restserver_rule($config);
            }
        }
        
        return $this;
    }

    /**
     * Obtenir une ou plusieurs variables d'entrées
     * @param string|NULL $key
     * @return mixed
     */
    public function input($key = NULL)
    {
        if ($key !== NULL) {
            return (isset($this->field_input[$key])) ? $this->field_input[$key] : FALSE;
        }

        return $this->field_input;
    }

    /**
     * Obtenir une ou plusieurs alias
     * @return array
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * Obtenir le protocole
     * @return array
     */
    public function protocol()
    {
        return $this->protocol;
    }

    /**
     * Obtenir la liste des champs
     * @return array
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Sérialisation des filtres
     * @param string $filter
     * @return array
     */
    public function filter($filter)
    {
        $filters = array();

        if ( ! empty($filter)) {
            $filter = json_decode($filter, TRUE);

            foreach ($filter as $value) {
                if (isset($value['property'])) {
                    if ( ! isset($filters[$value['property']])) {
                        $filters[$value['property']] = $value['value'];
                    } else {
                        $filters[$value['property']] .= ',' . $value['value'];
                    }
                }
            }
        }

        return $filters;
    }
    
    /**
     * Sérialisation des tries
     * @param string $sort
     * @return array
     */
    public function sort($sort)
    {
        $sorts = array();

        if ( ! empty($sort)) {
            $sort = json_decode($sort, TRUE);

            foreach ($sort as $value) {
                if (isset($value['property'])) {
                    if ( ! isset($sorts[$value['property']])) {
                        $sorts[$value['property']] = $value['direction'];
                    } else {
                        $sorts[$value['property']] .= ',' . $value['direction'];
                    }
                }
            }
        }

        return $sorts;
    }

    /**
     * Les données de la méthode Get
     * @param string|null $index
     * @param boolean $xss_clean
     * @return mixed
     */
    public function get($index = NULL, $xss_clean = FALSE)
    {
        return $this->_fetch_from_array('get', $index, $xss_clean);
    }

    /**
     * Les données de la méthode Post
     * @param string|null $index
     * @param boolean $xss_clean
     * @return mixed
     */
    public function post($index = NULL, $xss_clean = FALSE)
    {
        return $this->_fetch_from_array('post', $index, $xss_clean);
    }

    /**
     * Les données de la méthode Put
     * @param string|null $index
     * @param boolean $xss_clean
     * @return mixed
     */
    public function put($index = NULL, $xss_clean = FALSE)
    {
        return $this->_fetch_from_array('put', $index, $xss_clean);
    }

    /**
     * Les données de la méthode Delete
     * @param string|null $index
     * @param boolean $xss_clean
     * @return mixed
     */
    public function delete($index = NULL, $xss_clean = FALSE)
    {        
        return $this->_fetch_from_array('delete', $index, $xss_clean);
    }

    /**
     * Envoi une réponce au client
     * @param mixed $data
     * @param integer|null $code
     */
    public function response($data = array(), $code = NULL)
    {
        // Si il y a aucun data
        if (empty($data)) {
            $data = $this->protocol;
            $data['error'] = "Data is empty";
        }

        // Si il y a pas de code HTTP
        if (empty($code)) {
            $code = 200;
        }

        // Format de sortie
        $this->CI->output->set_content_type('json');

        // Définition du code HTTP
        $this->CI->output->set_status_header($code);

        // Si le data est du JSON
        $json = json_encode($data);

        // Format de sortie
        if (!empty($json)) {
            $this->CI->output->set_content_type('json');
        }

        // Encode le data
        $this->CI->output->set_output((!empty($json)) ? $json : $data);

        // Si le journal est activé
        if ($this->config['log']) {
            // Termine le bench
            $this->CI->benchmark->mark('restserver_end');
            $this->exectime = $this->CI->benchmark->elapsed_time('restserver_start', 'restserver_end');

            $log_model         = new stdClass();
            $log_model->method = ( ! empty($this->method)) ? $this->method : NULL;
            $log_model->url    = ( ! empty($this->url)) ? $this->url : NULL;
            $log_model->ip     = ( ! empty($this->ip)) ? $this->ip : NULL;
            $log_model->auth   = ($this->auth) ? 1 : 0;

            if ($this->config['log_extra']) {
                $this->output = $this->CI->output->get_output();

                $log_model->headers = ( ! empty($this->headers)) ? json_encode($this->headers) : NULL;
                $log_model->input = ( ! empty($this->input)) ? json_encode($this->input) : NULL;
                $log_model->output = ( ! empty($this->output)) ? $this->output : NULL;
            }

            $log_model->httpcode = $code;
            $log_model->exectime = $this->exectime;
            $log_model->dateinsert = date('Y-m-d H:i:s');

            // Enregistre le journal
            $this->_set_log($log_model);
        }
    }

    /**
     * Les données entrantes
     * @param string|null $key
     * @param boolean $xss_clean
     * @return array|booblean
     */
    private function _fetch_from_array($method, $index = NULL, $xss_clean = FALSE)
    {
        // Si l'index n'est définie on récupère l'ensemble de l'input
        if (empty($index)) {
            $index = array_keys($this->input[$method]);
        }

        // Si l'index est est tableau d'index
        if (is_array($index)) {
            $output = array();

			foreach ($index as $i) {
				$output[$i] = $this->_fetch_from_array($method, $i, $xss_clean);
			}

			return $output;
        }

        if (!isset($this->input[$method][$index])) {
            return NULL;
		}

        if ($xss_clean === TRUE) {
            return $this->CI->security->xss_clean($this->input[$method][$index]);
        }

        return $this->input[$method][$index];
    }

    /**
     * Si le protocol est de type HTTPS
     * @return boolean
     */
    private function _is_sslprotocol()
    {
        return ($this->CI->input->server('HTTPS') == 'on');
    }

    /**
     * Envoi les entêtes pour le cross domaine
     */
    private function _cross_domain()
    {
        // Autorisation des méthode
        $this->_set_header('Access-Control-Allow-Methods: '.implode(',', $this->config['allow_methods']));

        // Autorisation des en-têtes
        $this->_set_header('Access-Control-Allow-Headers: '.implode(',', $this->config['allow_headers']));

        // Autorisation credential
        if ($this->config['allow_credentials'] && $this->config['allow_credentials']) {
            $this->_set_header('Access-Control-Allow-Credentials: true');
        }

        // Autorise tout le monde
        if ($this->config['allow_origin'] === FALSE) {
            $this->_set_header('Access-Control-Allow-Origin: '.(( ! empty($this->headers['Origin'])) ? $this->headers['Origin'] : $this->ip));

        // Autorise une liste
        } else if (is_array($this->config['allow_origin']) && in_array($this->ip, $this->config['allow_origin'])) {
            $this->_set_header('Access-Control-Allow-Origin: '.$this->ip);
            
        // Autrement seulement un host
        } else if (!empty($this->config['allow_origin'])) {
            $this->_set_header('Access-Control-Allow-Origin: '.$this->config['allow_origin']);
        }
    }

    /**
     * Retourne la méthode
     * @return string la méthode
     */
    private function _get_method()
    {
        $method = $this->CI->input->server('REQUEST_METHOD');
        return ( ! empty($method)) ? strtolower($method) : '';
    }

    /**
     * Retourne l'URL
     * @return string
     */
    private function _get_url()
    {
        $url = current_url();
        return ( ! empty($url)) ? $url : '';
    }

    /**
     * Retourne l'adresse IP
     * @return string
     */
    private function _get_ip()
    {
        $ip = $this->CI->input->ip_address();
        return ( ! empty($ip)) ? $ip : '';
    }

    /**
     * Retourne la liste des en-têtes
     * @return array
     */
    private function _get_headers()
    {
        $headers = $this->CI->input->request_headers(TRUE);
        return ( ! empty($headers)) ? $headers : array();
    }

    /**
     * Retourne toutes les données entrantes
     * @return array
     */
    private function _get_input()
    {
        $get = NULL;
        $post = NULL;
        $put = NULL;
        $patch = NULL;
        $delete = NULL;

        switch ($this->method) {
            case 'get':
                $_get = $this->CI->input->get();
                $_uri = $this->CI->uri->ruri_to_assoc();
                
                // Fusionne les données Get + Uri
                $get = array_merge((array)$_get, (array)$_uri);
                break;
            case 'post':
                $post = $this->CI->input->post();

                // Si les données entrantes sont un POST normal
                if (!empty($post)) {
                    break;
                }
            case 'patch':
            case 'put':
            case 'delete':
                // Récupère les données entrantes
                $input = file_get_contents('php://input');

                // Si les données sont en JSON
                ${$this->method} = @json_decode($input, TRUE);

                // Si les données sont en HTTP
                if (empty(${$this->method})) {
                    parse_str($input, ${$this->method});
                }
        }

        // Renvoi les données entrantes
        return array(
            'get'    => (is_array($get)) ? $get : array(),
            'post'   => (is_array($post)) ? $post : array(),
            'patch'  => (is_array($patch)) ? $patch : array(),
            'put'    => (is_array($put)) ? $put : array(),
            'delete' => (is_array($delete)) ? $delete : array()
        );
    }

    /**
     * Authentication
     * @return boolean
     */
    private function _authentication()
    {
        // Si l'autentification par HTTP est activé et qu'il existe une surcharge
        if (method_exists($this->controller, '_auth')) {
            return call_user_func_array(array($this->controller, '_auth'), array());
        }

        return TRUE;
    }

    /**
     * Retourne les règles
     * @return array
     */
    private function _get_rules()
    {
        $rules = array();

        // Si le tableau des champs n'est pas vide
        if ( ! empty($this->rules)) {
            foreach ($this->rules as $value) {
                if ($value instanceof Restserver_rule) {
                    // Récupération des règles
                    $rule = $value->get();
                    
                    // Si il n'y a pas de règle
                    if ($rule !== NULL) {
                        $rules[] = $rule;
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Création des alias
     * @return array
     */
    private function _get_alias()
    {
        $alias = array();

        // Si des champs existent
        if ( ! empty($this->fields)) {
            foreach ($this->fields as $field) {
                // Si se sont des objects de type Restserver_field
                if ($field instanceof Restserver_field) {
                    // Si il y a plusieurs ojects
                    if (strstr($field->alias, '|') !== FALSE) {
                        $alias_array = explode('|', $field->alias);

                    // Si il y a qu'un seul object
                    } else {
                        $alias_array = array($field->alias);
                    }

                    foreach ($alias_array as $value) {

                        // Si il n'y a pas d'espace de nom
                        if (strstr($value, '.') === FALSE) {
                            $value = "default.$value";
                        }

                        // Valeur de l'entrée
                        if ((isset($this->input[$this->method][$field->input]))) {
                            $input_value = $this->input[$this->method][$field->input];
                        } else {
                            $input_value = NULL;
                        }

                        // Création des espaces de nom
                        $alias = array_replace_recursive($alias, $this->_namespace_recursive(explode('.', $value), $input_value));
                    }
                }
            }
        }

        return $alias;
    }

    /**
     * Espace de nom récursif
     * @param array $spaces
     * @param mixe $value
     * @param array $return
     * @return array
     */
    private function _namespace_recursive(array $spaces, $value = NULL, array $return = array())
    {
        $space = array_shift($spaces);

        $return[$space] = $return;

        if ( ! empty($spaces)) {
            $return[$space] = $this->_namespace_recursive($spaces, $value, $return[$space]);
        } else  {
            $return[$space] =& $value;
        }

        return $return;
    }

    /**
     * Création des champs d'entrée
     * @return array
     */
    private function _get_field_input()
    {
        $input = array();

        // Si des champs existent
        if ( ! empty($this->fields)) {
            foreach ($this->fields as $field) {

                // Si se sont des objects de type Restserver_field
                if ($field instanceof Restserver_field) {
                    // Si la donnée d'entrée existe
                    if (isset($this->input[$this->method][$field->input])) {
                        $input[$field->input] =& $this->input[$this->method][$field->input];

                    // Si elle n'existe pas ça valeur est NULL
                    } else {
                        $input[$field->input] = NULL;
                    }
                }
            }
        }

        return $input;
    }

    /**
     * Retourne les documentations
     * @return array
     */
    private function _get_docs()
    {
        $docs = array();

        // Si le tableau des champs n'est pas vide
        if ( ! empty($this->fields)) {
            foreach ($this->fields as $field) {
                if ($field instanceof Restserver_field) {
                    $docs[$field->input] = $field->comment;
                }
            }
        }

        return $docs;
    }

    /**
     * Retourne la documentation HAR en fonction de la méthode demandée http://www.softwareishard.com/blog/har-12-spec/#postData
     * @return array
     */
    private function _get_doc_har($method)
    {
        $doc = array();
        // Si le tableau des champs n'est pas vide
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $doc['method']      = strtoupper($method);
                $doc['cookies']     = array();
                $doc['url']         = $this->_get_url();
                $doc['httpVersion'] = $this->CI->input->server('SERVER_PROTOCOL');
                $doc['queryString'] = array();
                $doc['postData'] = array();
                
                foreach ($this->fields as $field) {
                    if (in_array('required_'. strtolower($method), explode('|', $field->rules))) {
                                                
                        // Si c'est un GET
                        if ($method == 'get') {
                            $doc['queryString'][] = array(
                                'name'  => $field->input,
                                'value' => $field->label,
                                'comment' => $field->comment,
                            );
                            
                        // Pour toutes les autres requêtes
                        } else {
                            $doc['postData']['mimeType']            = "application/json";
                            $doc['postData']['text'][$field->input] = $field->label;
                        }
                    }
                }
                
                if (!empty($doc['postData']['text'])) {
                    $doc['postData']['text'] = json_encode($doc['postData']['text']);
                }
                
                $doc['headers'] = array(
                    array(
                        "name"  => "Accept",
                        "value" => "application/json"
                    ),
                    array(
                        "name"  => "Content-Type",
                        "value" => "application/json"
                    ),
                );
            }
        }
        return $doc;
    }


    /**
     * Insert les évènements dans un journal
     * @param stdClass $log_model
     */
    private function _set_log(stdClass $log_model)
    {
        switch ($this->config['log_driver']) {
            case 'db':
                // Connection à la base de donnée
                $this->CI->db_{$this->config['log_db_name']} = $this->CI->load->database($this->config['log_db_name'], TRUE);

                // Insertion des données
                $this->CI->db_{$this->config['log_db_name']}->insert($this->config['log_db_table'], $log_model);
                break;
            case 'file':
            default:
                $file_path = ( ! empty($this->config['log_file_path'])) ? $this->config['log_file_path'] : sys_get_temp_dir();
                $file = "$file_path/{$this->config['log_file_name']}";

                if (touch($file)) {
                    if (is_file($file) && is_writable($file)) {
                        $log = "method: $log_model->methode".PHP_EOL;
                        $log .= " url: $log_model->url".PHP_EOL;
                        $log .= " ip: $log_model->ip".PHP_EOL;
                        $log .= " user: $log_model->user".PHP_EOL;
                        $log .= " password: $log_model->password".PHP_EOL;
                        $log .= " key: $log_model->key".PHP_EOL;

                        if ($this->config['log_extra']) {
                            $log .= " headers: $log_model->headers".PHP_EOL;
                            $log .= " input: $log_model->input".PHP_EOL;
                            $log .= " output: $log_model->output".PHP_EOL;
                        }

                        $log .= " httpcode: $log_model->httpcode".PHP_EOL;
                        $log .= " exectime: $log_model->exectime".PHP_EOL;
                        $log .= " date: $log_model->dateinsert".PHP_EOL;

                        error_log($log, 3, $file);
                    }
                }
        }
    }
    
    /**
     * Retourne les documentations
     */
    private function _set_header($value)
    {
        // Si le mode débug est activé, utilise le header natif
        if ($this->config['debug']) {
            header($value);
            
        // SUtilise le header du framework
        } else {
            $this->CI->output->set_header($value);
        }
    }
    
}

/* End of file Restserver.php */
/* Location: ./libraries/Restserver/Restserver.php */
