<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Defines usefull constants
const RESTSERVER_VERSION = '3.0.0';
const RESTSERVER_BASEPATH = __DIR__;

class Restserver
{
    // Defines the CI instance
    protected $CI;

    // Defines Restserver Classes to load
    protected $_core_classes = array(
        // Defines System Core Classes
        'Core/Config',
        'Core/Server',
        'Core/Rule',
        'Core/Validation',
        'Core/Input',
        'Core/Output',
        // Defines System Input Classes
        'Input/Data',
        'Input/Filter',
        'Input/Limit',
        'Input/Page',
        'Input/Sorter',
        'Input/Start',
        // Defines System Output Classes
        'Output/Cross_domain',
        'Output/Doc',
        'Output/Har',
        'Output/Response',
        // Defines System Log Classes
        'Log/Modeld'
    );

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

        var_dump($this);
    }

    /**
     * Load every Restserver Classes
     * @method initialize
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return void
     */
    public function initialize()
    {
        if (! empty($this->_core_classes)) {
            foreach ($this->_core_classes as $class_path) {
                if (file_exists(RESTSERVER_BASEPATH.'/'.$class_path.'.php')) {
                    // Require class path
                    require_once(RESTSERVER_BASEPATH.'/'.$class_path.'.php');
                } else {
                    // Define class name
                    $class_name = explode('/', $class_path);

                    // Returns Class loader error
                    show_error('Unable to locate the specified class: '.$class_name[1].'.php', 503, 'Restserver '.RESTSERVER_VERSION.' Error');
                }
            }
        }
    }

    public function run()
    {
    }
    public function set_rules()
    {
    }
}

/* End of file Restserver.php */
/* Location: ./Restserver/Restserver.php */
