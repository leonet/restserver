<?php
namespace Restserver\Core;

class Output
{
    protected $CI;
    protected $config;
            
    function __construct(&$config)
    {
        $this->CI =& get_instance();
        $this->config = $config;
    }
    
    public function set_header($value)
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