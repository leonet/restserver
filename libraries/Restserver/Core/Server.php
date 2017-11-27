<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Server
{
    public $config;
    public $cross_domain;
    public $input;
    public $output;
    protected $CI;

    /**
     * Class Constructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function initialize()
    {
        
    }

    public function run(Restserver_Controller &$controller, $call, $params)
    {
        // Envoi les autorisations pour le cross-domain
        $this->cross_domain->run();

        // Si la requête est de type options (cross domain)
        if ($this->input->method() === 'options') {
            $this->output->response(array_merge($this->output->get_protocol(), array(
                'status' => true
            )), 200);

            return true;
        }

        // Si le protocole SSL est obligatoire
        if ($this->config->get('force_https') && !$this->input->is_ssl()) {
            $this->output->response(array_merge($this->output->get_protocol(), array(
                'status' => false,
                'error'  => 'Unsupported protocol'
            )), 403);

            return false;
        }

        // Si la requête est en ajax
        if ($this->config->get('ajax_only') && !$this->CI->input->is_ajax_request()) {
            $this->response(array_merge($this->output->get_protocol(), array(
                'status' => false,
                'error'  => 'Only AJAX requests are accepted'
            )), 505);

            return false;
        }

        // Authentification
        if ($this->config->get('auth_http')) {
            if (!$this->authentication($controller)) {
                $this->output->response(array_merge($this->output->get_protocol(), array(
                    'status' => false,
                    'error'  => 'Forbidden'
                )), 403);
                
                return false;
            }
        }

        // Si la méthode existe
        if (!method_exists($controller, $this->input->method())) {
            $this->output->response(array_merge($this->output->get_protocol(), array(
                'status' => false,
                'error'  => 'Method not found'
            )), 405);

            return false;
        }

        // Si la documentation est demandé
        if (isset($this->input['get']['help'])) {
            // Récupère les fields pour la documentation
            $doc = $this->output->doc()->get();

            // Si il existe une docuementation
            if (!empty($doc)) {
                $this->output->response(array(
                    'status' => true,
                    'value'  => $doc
                ), 200);

                return true;
            }
        }

        // Si la documentation HAR est demandée (http://www.softwareishard.com/blog/har-12-spec/#request)
        if (isset($this->input['get']['har']) && $har = $this->input['get']['har']) {
            // Récupère les fields pour la documentation
            $doc = $this->_get_doc_har($har);

            // Si il existe une docuementation
            if (!empty($doc)) {
                $this->output->response($doc, 200);

                return true;
            }
        }

        // Récupère les règles
        $rules = $this->_get_rules();

        // Si des règles existent
        if (!empty($rules)) {
            // Vérification des données entrantes
            $this->CI->form_validation->set_data($this->input[$this->method]);
            $this->CI->form_validation->set_rules($rules);
            $this->CI->form_validation->set_error_delimiters('', '');

            // Si le validateur a rencontré une ou plusieurs erreurs
            if ($this->CI->form_validation->run() === false) {
                exit('fdfsfsdfsd');
                $errors = $this->CI->form_validation->error_array();

                $this->output->response(array(
                    'status' => false,
                    'error'  => (!empty($errors)) ? $errors : 'Unsupported data validation'
                ), 400);

                return false;
            }
        }

        // Création des input
        //$this->field_input = $this->_get_field_input();

        // Création des alias
        //$this->alias = $this->_get_alias();

        // Exécute la méthode
        call_user_func_array(array($controller, $method), $params);

        return true;
    }
    
    /**
     * Appelle une méthode d'autentificiation, si elle existe
     * @param \CI_Controller $controller
     * @return boolean
     */
    protected function authentication(\CI_Controller &$controller)
    {
        // Si l'autentification par HTTP est activé et qu'il existe une surcharge
        if (method_exists($controller, '_auth')) {
            return call_user_func_array(array($controller, '_auth'), array());
        }

        return true;
    }
    
}

/* End of file Server.php */
/* Location: ./Restserver/Core/Server.php */
