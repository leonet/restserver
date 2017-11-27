<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Output
{
    protected $CI;
    protected $config;

    function __construct(\Restserver\Core\Config $config)
    {
        $this->CI =& get_instance();
        $this->config =& $config->getInstance();
    }

    public function set_header($value)
    {
        // Si le mode débug est activé, utilise le header natif
        if ($this->config->get('debug')) {
            header($value);
        } else {
            // Utilise le header du framework
            $this->CI->output->set_header($value);
        }
    }
}

/* End of file Output.php */
/* Location: ./Restserver/Core/Output.php */
