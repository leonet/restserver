<?php
namespace Restserver\Input;

defined('BASEPATH') or exit ('No direct script access allowed');

class Data
{
    protected $CI;

    /**
     * Class Constructor
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
}

/* End of file Data.php */
/* Location: ./Restserver/Input/Data.php */
