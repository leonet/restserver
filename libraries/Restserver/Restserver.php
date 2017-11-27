<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Defines classes
require(__DIR__.'/Core/Config.php');
require(__DIR__.'/Core/Server.php');
require(__DIR__.'/Core/Rule.php');
require(__DIR__.'/Core/Validation.php');
require(__DIR__.'/Core/Input.php');
require(__DIR__.'/Core/Output.php');
require(__DIR__.'/Input/Data.php');
require(__DIR__.'/Input/Filter.php');
require(__DIR__.'/Input/Limit.php');
require(__DIR__.'/Input/Page.php');
require(__DIR__.'/Input/Sorter.php');
require(__DIR__.'/Input/Start.php');
require(__DIR__.'/Output/Cross_domain.php');
require(__DIR__.'/Output/Doc.php');
require(__DIR__.'/Output/Har.php');
require(__DIR__.'/Output/Response.php');
require(__DIR__.'/Log/Model.php');

class Restserver
{
    // Defines the CI instance
    protected $CI;
    
    protected $config;
    
    protected $server;

    /**
     * Class cosntructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return array  $this  Class object
     */
    public function __construct(array $config = array())
    {
        // Gets the CI instance
        $this->CI =& get_instance();

        // Loads classes
        $this->initialize($config);
    }

    /**
     * Load every Restserver Classes
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return void
     */
    public function initialize(array $config = array())
    {
        $this->config = new \Restserver\Core\Config($config);
        $this->server = new \Restserver\Core\Server($this->config);
    }

    /**
     * Run RestServer
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return [type] [description]
     */
    public function run(&$controller, $call, $params)
    {
        $this->server->run($controller, $call, $params);
    }

    public function set_rules($rules)
    {
        $this->input->setRules($rules);
    }
    
    public function input($key)
    {
        return $this->input->getInput($rules);
    }
    
    public function alias()
    {
        return $this->input->getAlias();
    }
    
    public function post($key)
    {
        return $this->input->post();
    }
    
     public function get()
    {
         return $this->input->get();
    }
    
     public function put()
    {
         return $this->input->put();
    }
    
     public function patch()
    {
         return $this->input->patch();
    }
    
     public function delete()
    {
         return $this->input->delete();
    }
}

/* End of file Restserver.php */
/* Location: ./Restserver/Restserver.php */
