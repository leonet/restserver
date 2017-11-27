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
require(__DIR__.'/Output/Cross_domain.php');
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
     * Defines the CI instance
     * @var object
     */
    protected $config;

    /**
     * Defines the CI instance
     * @var object
     */
    protected $server;

    /**
     * Defines the CI instance
     * @var object
     */
    protected $rules;

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
        $this->rules = new \Restserver\Core\Rules();
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
    public function input($key)
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
    public function post($key)
    {
        return $this->input->post();
    }

    /**
     * Input GET Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function get()
    {
        return $this->input->get();
    }

    /**
     * Input PUT Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function put()
    {
        return $this->input->put();
    }

    /**
     * Input PATCH Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function patch()
    {
        return $this->input->patch();
    }

    /**
     * Input DELETE Protocol
     * @author Yoann VANITOU <y.vanitou@santiane.fr>
     * @return \Restserver\Core\Input
     */
    public function delete()
    {
        return $this->input->delete();
    }
}

/* End of file Restserver.php */
/* Location: ./Restserver/Restserver.php */
