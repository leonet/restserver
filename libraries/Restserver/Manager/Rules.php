<?php
namespace Restserver\Manager;

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
class Rules
{
    protected $rules = array();
    
    public function set(array $rules = array())
    {
        foreach ($rules as $rule) {
            $this->rules[] = new \Restserver\Core\Rule($rule);
        }
    }

    public function get()
    {
        return $this->rules;
    }

}

/* End of file Restserver_rule.php */
/* Location: ./libraries/Restserver/Restserver_rule.php */
