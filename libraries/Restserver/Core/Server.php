<?php
namespace \Restserver\Core;

class Server
{
    $this->config;
    $this->cross_domain;
    $this->input;
    $this->output;
    
    function __construct()
    {
        
    }
    
    public fonction initialize


    public function run(Restserver_Controller &$controller, $call, $params)
    {
        // Envoi les autorisations pour le cross-domain
        $this->cross_domain->run();

        // Si la requête est de type options (cross domain)
        if ($this->method === 'options') {
            $this->response(array(
                'status' => true
            ), 200);

            return true;
        }

        // Si le protocole SSL est obligatoire
        if ($this->config['force_https'] && !$this->_is_sslprotocol()) {
            $this->response(array(
                'status' => false,
                'error'  => 'Unsupported protocol'
            ), 403);

            return false;
        }

        // Si la requête est en ajax
        if ($this->config['ajax_only'] && !$this->CI->input->is_ajax_request()) {
            $this->response(array(
                'status' => false,
                'error'  => 'Only AJAX requests are accepted'
            ), 505);

            return false;
        }

        // Authentification
        if ($this->config['auth_http']) {
            if (!$this->auth = $this->_authentication()) {
                return false;
            }
        }

        // Si la méthode existe
        if (!method_exists($this->controller, $this->method)) {
            $this->response(array(
                'status' => false,
                'error'  => 'Method not found'
            ), 405);

            return false;
        }

        // Si la documentation est demandé
        if (isset($this->input['get']['help'])) {
            // Récupère les fields pour la documentation
            $docs = $this->_get_docs();

            // Si il existe une docuementation
            if (!empty($docs)) {
                $this->response(array(
                    'status' => true,
                    'value'  => $docs
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
                $this->response($doc, 200);

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

                $this->response(array(
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

}