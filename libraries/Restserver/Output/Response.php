<?php
namespace \Restserver\Output;

class Response
{
    protected $log;

    function __construct(\Restserver\Log\Model $log)
    {
        $this->CI =& get_instance();
        
        $this->log =& $log;
    }
    
    /**
     * Envoi une réponse au client
     * @param mixed $data
     * @param integer|null $code
     */
    public function set($data = array(), $code = null)
    {
        // Si il y a aucun data
        if (empty($data)) {
            $data          = $this->protocol;
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

        /*
         * Si le data est du JSON
         *
         * Pour produire un JSON plus clair :
         * JSON_UNESCAPED_SLASHES = Ne pas échapper les caractères /. Disponible depuis PHP 5.4.0.
         * JSON_UNESCAPED_UNICODE = Encode les caractères multi-octets Unicode littéralement (le comportement par
         *                          défaut est de les échapper, i.e. \uXXXX). Disponible depuis PHP 5.4.0.
         */
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

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
            $log_model->method = (!empty($this->method)) ? $this->method : null;
            $log_model->url    = (!empty($this->url)) ? $this->url : null;
            $log_model->ip     = (!empty($this->ip)) ? $this->ip : null;
            $log_model->auth   = ($this->auth) ? 1 : 0;
            
            if ($this->config['log_extra']) {
                $this->output = $this->CI->output->get_output();

                $log_model->headers = (!empty($this->headers)) ? json_encode($this->headers) : null;
                $log_model->input   = (!empty($this->input))   ? json_encode($this->input) : null;
                $log_model->output  = (!empty($this->output))  ? $this->output : null;
            }

            $log_model->httpcode   = $code;
            $log_model->exectime   = $this->exectime;
            $log_model->dateinsert = date('Y-m-d H:i:s');

            // Enregistre le journal
            $this->_set_log($log_model);
        }
    }

}