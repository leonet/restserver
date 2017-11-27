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
interface Restserver_interface
{
    public function post();
    
    public function get();
    
    public function put();
    
    public function delete();
}

/* End of file Restserver_interface.php */
/* Location: ./libraries/Restserver/Restserver_interface.php */
