<?php
namespace \Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Output
{
    protected $CI;
    protected $config;

    public function __construct(&$config)
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

/* End of file Output.php */
/* Location: ./Restserver/Core/Output.php */
