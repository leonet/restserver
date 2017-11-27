<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Output
{
    protected $CI;
    protected $config;
    protected $fields;

    function __construct(\Restserver\Core\Config $config, \Restserver\Core\Rules $rules)
    {
        $this->CI =& get_instance();
        $this->config =& $config;
        $this->rules =& $rules;
        
    }

    public function setHeader($value)
    {
        // Si le mode débug est activé, utilise le header natif
        if ($this->config->get('debug')) {
            header($value);
        } else {
            // Utilise le header du framework
            $this->CI->output->set_header($value);
        }
    }
    
    public function getProtocol()
    {
        return restserver_protocol();
    }
    
    public function doc()
    {
        return new \Restserver\Output\Doc($this->rules);
    }
}

/* End of file Output.php */
/* Location: ./Restserver/Core/Output.php */
