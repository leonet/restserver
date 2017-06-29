<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * Restserver (Librairie REST Serveur)
 * @author Yoann VANITOU
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link https://github.com/maltyxx/restserver
 */
require(FCPATH.'vendor/maltyxx/restserver/libraries/Restserver/Restserver_interface.php');

abstract class Restserver_controller extends MY_Controller implements Restserver_interface
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->add_package_path(FCPATH.'vendor/maltyxx/restserver');
        $this->load->library('restserver');
        $this->load->remove_package_path(FCPATH.'vendor/maltyxx/restserver');
    }

    /**
     * Remap
     * @param string $call
     * @param array $params
     */
    public function _remap($call, array $params = array())
    {
        $this->restserver->run($this, $call, $params);
    }
}

/* End of file Restserver_Controller.php */
/* Location: ./core/Restserver_Controller.php */
