<?php
namespace Restserver\Output;

defined('BASEPATH') or exit ('No direct script access allowed');

class Cross_domain
{
    protected $config;
    protected $output;

    public function __construct(\Restserver\Core\Config $config, \Restserver\Core\Input $input, \Restserver\Core\Output $output)
    {
        $this->config =& $config->getInstance();
        $this->input  =& $input->getInstance();
        $this->output =& $output->getInstance();
    }

    public function run()
    {

        // Récupération des en-têtes
        $headers = $this->input->headers();

        // Récupératino de l'ip
        $ip = $this->input->ip();

        // Récupération de l'origine
        $origin = $this->config->get('allow_origin');

        // Autorisation des méthode
        $this->output->set_header('Access-Control-Allow-Methods: '.implode(',', $this->config->get('allow_methods')));

        // Autorisation des en-têtes
        $this->output->set_header('Access-Control-Allow-Headers: '.implode(',', $this->config->get('allow_headers')));

        // Autorisation credential
        if ($this->config->get('allow_credentials') &&  $this->config->get('allow_credentials')) {
            $this->output->set_header('Access-Control-Allow-Credentials: true');
        }

        // Autorise tout le monde
        if ($this->config->get('allow_origin') === false) {
            $this->output->set_header('Access-Control-Allow-Origin: '.((!empty($headers['Origin'])) ? $headers['Origin'] : $ip));

        // Autorise une ip
        } elseif (is_array($this->config('allow_origin')) && in_array($ip, $this->config('allow_origin'))) {
            $this->output->set_header('Access-Control-Allow-Origin: '.$ip);

        // Autrement seulement un host
        } elseif (!empty($origin)) {
            $this->output->set_header('Access-Control-Allow-Origin: '.$this->config('allow_origin'));
        }
    }
}

/* End of file Cross_domain.php */
/* Location: ./Restserver/Output/Cross_domain.php */
