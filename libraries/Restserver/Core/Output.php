<?php
namespace \Restserver\Core;

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

            // SUtilise le header du framework
        } else {
            $this->CI->output->set_header($value);
        }
    }
    
}