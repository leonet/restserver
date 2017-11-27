<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Defines classes
require(__DIR__.'/Core/Config');
require(__DIR__.'/Core/Server');
require(__DIR__.'/Core/Rule');
require(__DIR__.'/Core/Validation');
require(__DIR__.'/Core/Input');
require(__DIR__.'/Core/Output');
require(__DIR__.'/Input/Data');
require(__DIR__.'/Input/Filter');
require(__DIR__.'/Input/Limit');
require(__DIR__.'/Input/Page');
require(__DIR__.'/Input/Sorter');
require(__DIR__.'/Input/Start');
require(__DIR__.'/Output/Cross_domain');
require(__DIR__.'/Output/Doc');
require(__DIR__.'/Output/Har');
require(__DIR__.'/Output/Response');
require(__DIR__.'/Log/Mode');

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
