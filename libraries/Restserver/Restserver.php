<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Defines System Classes to load
 */
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
require(__DIR__.'/Output/Cross.php');
require(__DIR__.'/Output/Doc.php');
require(__DIR__.'/Output/Har.php');
require(__DIR__.'/Output/Response.php');
require(__DIR__.'/Log/Model.php');

/**
 * RestServer Class
 */
class Restserver
{
    /**
     * Defines the CI instance
     * @var object
     */
    protected $CI;

    /**
     * Defines the Restserver Configuration
     * @var object
     */
    protected $config;

    /**
     * Defines the Restserver Server
     * @var object
     */
    protected $server;

    /**
     * Defines the Restserver Rules
     * @var object
     */
    protected $rules;

    /**
     * Defines the Restserver Input
     * @var object
     */
    protected $input;

    /**
     * Class constructor
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return void
     */
    public function __construct(array $config = array())
    {
        // Gets the CI instance
        $this->CI =& get_instance();

        // Init classes
        $this->initialize($config);
    }

    /**
     * Instantiate every Restserver System Classes
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return void
     */
    public function initialize(array $config = array())
    {
        $this->config = new \Restserver\Core\Config($config);
        $this->server = new \Restserver\Core\Server();
        $this->input = new \Restserver\Manager\Input();
        $this->rules = new \Restserver\Manager\Rules();
    }

    /**
     * Run Restserver
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return void
     */
    public function run(&$controller, $call, $params)
    {
        $this->server->run($controller, $call, $params);
    }

    /**
     * Set the Restserver rules
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @param  array    $rules Array of rules
     * @return void
     */
    public function set_rules($rules)
    {
        $this->rules->set($rules);
    }

    /**
     * Set the Input
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @param  array    $key
     * @return \Restserver\Core\Input
     */
    public function input($key = null)
    {
        return $this->input->getData($key);
    }

    /**
     * Set the Aliases
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function alias()
    {
        return $this->input->getAlias();
    }

    /**
     * Input POST Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function post($key = null)
    {
        return $this->input->post($key);
    }

    /**
     * Input GET Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function get($key = null)
    {
        return $this->input->get($key);
    }

    /**
     * Input PUT Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function put($key = null)
    {
        return $this->input->put($key);
    }

    /**
     * Input PATCH Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function patch($key = null)
    {
        return $this->input->patch($key);
    }

    /**
     * Input DELETE Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function delete($key = null)
    {
        return $this->input->delete($key);
    }
}

/* End of file Restserver.php */
/* Location: ./Restserver/Restserver.php */
