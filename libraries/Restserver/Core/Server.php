<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Server
{
    public $config;
        
    public $input;
    
    public $output;
    
    public function run(Restserver_Controller &$controller, $call, $params)
    {
        // Envoi les autorisations pour le cross-domain
        $cross = new \Restserver\Output\Cross($this->config, $this->input, $this->output);
        $cross->run();

        // Si la requête est de type options (cross domain)
        if ($this->input->getMethod() === 'options') {
            $this->output->setResponse(array_merge($this->output->getProtocol(), array(
                'status' => true
            )), 200);

            return true;
        }

        // Si le protocole SSL est obligatoire
        if ($this->config->get('force_https') && !$this->input->isSsl()) {
            $this->output->setResponse(array_merge($this->output->getProtocol(), array(
                'status' => false,
                'error'  => 'Unsupported protocol'
            )), 403);

            return false;
        }

        // Si la requête est en ajax
        if ($this->config->get('ajax_only') && !$this->input->is_ajax_request()) {
            $this->setResponse(array_merge($this->output->getProtocol(), array(
                'status' => false,
                'error'  => 'Only AJAX requests are accepted'
            )), 505);

            return false;
        }

        // Authentification
        if ($this->config->get('auth_http')) {
            if (!$this->authentication($controller)) {
                $this->output->setResponse(array_merge($this->output->getProtocol(), array(
                    'status' => false,
                    'error'  => 'Forbidden'
                )), 403);
                
                return false;
            }
        }

        // Si la méthode existe
        if (!method_exists($controller, $this->input->getMethod())) {
            $this->output->setResponse(array_merge($this->output->getProtocol(), array(
                'status' => false,
                'error'  => 'Method not found'
            )), 405);

            return false;
        }
        
        // Récupération des data
        $data = $this->input->getData();

        // Si la documentation est demandée
        if ($this->input->get('help')) {
            $help = new \Restserver\Output\Help();
            
            // Récupère les fields pour la documentation
            $comments = $help->get();
            
            // Si il existe une docuementation
            if (!empty($comments)) {
                $this->output->setResponse(array_merge($this->output->getProtocol(), array(
                    'status' => true,
                    'value'  => $comments
                )), 200);

                return true;
            }
        }

        // Si la documentation HAR est demandée (http://www.softwareishard.com/blog/har-12-spec/#request)
        if ($this->input->get('har')) {
            $har = new \Restserver\Output\Har();
            
            // Récupère les fields pour la documentation
            $documentation = $har->get();

            // Si il existe une docuementation
            if (!empty($documentation)) {
                $this->output->setResponse($documentation, 200);

                return true;
            }
        }
        
        // Validation des donées
        $validation = new \Restserver\Core\Validation($this->response);
        
        // Définition du data
        $validation->setData($data);
        
        // Définition des règles
        $validation->setRules($this->fields->getRules());
        
        // Lance la validation
        if ($validation->run() === false) {
            return false;
        }

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
