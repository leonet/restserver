<?php

namespace \Restserver\Output;

class Cross
{
    public $config;
           
    function __construct(&$config)
    {
        $this->config = $config;
    }
    
    public function functionName($param)
    {
        // Autorisation des méthode
        $this->set_header('Access-Control-Allow-Methods: '.implode(',', $this->config['allow_methods']));

        // Autorisation des en-têtes
        $this->set_header('Access-Control-Allow-Headers: '.implode(',', $this->config['allow_headers']));

        // Autorisation credential
        if ($this->config['allow_credentials'] && $this->config['allow_credentials']) {
            $this->set_header('Access-Control-Allow-Credentials: true');
        }

        // Autorise tout le monde
        if ($this->config['allow_origin'] === false) {
            $this->set_header('Access-Control-Allow-Origin: '.((!empty($this->headers['Origin'])) ? $this->headers['Origin'] : $this->ip));

            // Autorise une liste
        } else if (is_array($this->config['allow_origin']) && in_array($this->ip, $this->config['allow_origin'])) {
            $this->set_header('Access-Control-Allow-Origin: '.$this->ip);

            // Autrement seulement un host
        } else if (!empty($this->config['allow_origin'])) {
            $this->set_header('Access-Control-Allow-Origin: '.$this->config['allow_origin']);
        }
    }

}
