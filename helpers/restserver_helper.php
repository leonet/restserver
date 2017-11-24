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
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Protocole REST
 *
 * @author Yoann Vanitou <yvanitou@gmail.com>
 *
 */

if (!function_exists('restserver_protocol')) {
    /**
     * Protocole REST
     * @return array Protocole
     */
    function restserver_protocol()
    {
        return $protocol = array(
            'status' => FALSE,
            'error'  => NULL,
            'value'  => NULL
        );
    }
}

/* End of file Restserver_helper.php */
/* Location: ./application/helpers/Restserver_helper.php */
