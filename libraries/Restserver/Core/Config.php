<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Config
{
    /**
     * Gets the CI instance
     * @var object $CI
     */
    protected $CI;

    /**
     * Restserver configuration
     * @var array $config
     */
    protected $config = array(
        'allow_methods' => array('GET', 'POST', 'PUT', 'DELETE'),
        'allow_headers' => array('X-RestServer'),
        'allow_credentials' => false,
        'allow_origin' => false,
        'force_https' => false,
        'ajax_only' => false,
        'auth_http' => false,
        'cache' => false,
        'debug' => false,
        'log' => false,
        'log_driver' => 'file',
        'log_db_name' => 'rest',
        'log_db_table' => 'log',
        'log_file_path' => '',
        'log_file_name' => 'rest.log',
        'log_extra' => false
    );

    /**
     * Class cosntructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return array  $this  Class object
     */
    public function construct(array &$config = array())
    {
        // Gets the CI instance
        $this->CI =& get_instance();

        // If any configuration data was sent to the class constructor
        empty($config) or $this->run($config);
    }

    /**
     * Initialize config parameters
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  array  $config Personal config vars
     * @return array         [description]
     */
    public function run(array $config = array())
    {
        // Append configuration to Config object
        if (!empty($config)) {
            foreach ($config as $config_key => $config_value) {
                (!property_exists($this, $config_key)) or $this->$config_key = $config_value;
            }
        }
    }

    /**
     * Returns Restserver configuration
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return array  $this  Restserver Configuration
     */
    public function getInstance()
    {
        // Return
        return $this;
    }

    /**
     * Returns Restserver configuration value by key
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return array  $this  Restserver Configuration
     */
    public function get($config_key = null)
    {
        if (empty($config_key)) {
            // Returns every config vars
            return get_object_vars($this);
        } elseif (isset($this->{$config_key})) {
            // Returns the asked config var
            return $this->{$config_key};
        } else {
            return false;
        }
    }
}

/* End of file Config.php */
/* Location: ./Restserver/Core/Config.php */
