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


    /**
     * Class cosntructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return array  $this  Class object
     */
    public function __construct()
    {
        // Gets the CI instance
        $this->CI =& get_instance();

        // Loads classes
        $this->initialize();
    }

    /**
     * Load every Restserver Classes
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * Run RestServer
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return [type] [description]
     */
    public function run($call, $params)
    {
        var_dump(new Restserver\Core\Server($this, $call, $params));
    }

    public function set_rules()
    {
    }
}

/* End of file Restserver.php */
/* Location: ./Restserver/Restserver.php */
