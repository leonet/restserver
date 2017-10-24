<?php
/**
 * REST Full server for Codeigniter 3
 * 
 * @author Yoann Vanitou <yvanitou@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link   https://github.com/maltyxx/restserver
 * @since   Version 2.1.0
 * @filesource
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * Restserver (Librairie REST Serveur)
 */
require(FCPATH.'vendor/maltyxx/restserver/libraries/Restserver/Restserver_interface.php');

abstract class Restserver_controller extends MY_Controller implements Restserver_interface
{
    public function __construct()
    {
        parent::__construct();
        
        $package_path = FCPATH.'vendor/maltyxx/restserver';
        
        // Chargement de la librairie
        $this->load->add_package_path($package_path)
            ->library('restserver')
            ->remove_package_path($package_path);
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
