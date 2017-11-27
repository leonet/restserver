<?php
namespace \Restserver\Input;

defined('BASEPATH') or exit ('No direct script access allowed');

class Sorter
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

/* End of file Sorter.php */
/* Location: ./Restserver/Input/Sorter.php */
