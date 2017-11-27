<?php
namespace Restserver\Input;

defined('BASEPATH') or exit ('No direct script access allowed');

class Start
{
    protected $CI;
    protected $value;

    /**
     * Class Constructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function get()
    {
        return $this->value;
    }

    public function set($value)
    {
        $this->value = (int) $value;
    }
}

/* End of file Start.php */
/* Location: ./Restserver/Input/Start.php */
