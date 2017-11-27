<?php
namespace \Restserver\Output;

defined('BASEPATH') or exit ('No direct script access allowed');

class Database
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

/* End of file Database.php */
/* Location: ./Restserver/Log/Drivers/Database.php */
