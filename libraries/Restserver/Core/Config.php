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
     */
    protected $allow_methods = array('GET', 'POST', 'PUT', 'DELETE');
    
    protected $allow_headers = array('X-RestServer');
    
    protected $allow_credentials = false;
    
    protected $allow_origin = false;
    
    protected $force_https = false;
    
    protected $ajax_only = false;
    
    protected $auth_http = false;
    
    protected $cache = false;
    
    protected $debug = false;
    
    protected $log = false;
    
    protected $log_driver = 'file';
    
    protected $log_db_name = 'rest';
    
    protected $log_db_table = 'log';
    
    protected $log_file_path = '';
    
    protected $log_file_name = 'rest.log';
    
    protected $log_extra = false;

    /**
     * Class cosntructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return array  $this  Class object
     */
    public function construct(array $config = array())
    {
        // Gets the CI instance
        $this->CI =& get_instance();

        // If any configuration data was sent to the class constructor
        $this->initialize($config);
    }

    /**
     * Initialize config parameters
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  array  $config Personal config vars
     * @return array         [description]
     */
    public function initialize(array $config = array())
    {
        // Append configuration to Config object
        if (!empty($config)) {
            foreach ($config as $config_key => $config_value) {
                if (isset($this->$config_key)) {
                    $this->$config_key = $config_value;
                }
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
