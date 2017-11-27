<?php
namespace \Restserver\Output;

defined('BASEPATH') or exit ('No direct script access allowed');

class File
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

/* End of file File.php */
/* Location: ./Restserver/Log/Drivers/File.php */
