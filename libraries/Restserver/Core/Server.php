<?php
namespace \Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Server
{
    protected $CI;

    /**
     * Class Constructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function run(Restserver_Controller &$controller, $call, $params)
    {
    }
}

/* End of file Server.php */
/* Location: ./Restserver/Core/Server.php */
