<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Rule
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

/* End of file Server.php */
/* Location: ./Restserver/Core/Server.php */
