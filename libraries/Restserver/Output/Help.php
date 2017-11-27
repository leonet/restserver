<?php
namespace Restserver\Output;

defined('BASEPATH') or exit ('No direct script access allowed');

class Doc
{
    protected $doc = array();

    public function __construct(\Restserver\Manager\Rules $rules)
    {
        // Si le tableau des champs n'est pas vide
        if (!empty($rules)) {
            foreach ($rules as $rule) {
                $this->doc[$rule->getName()] = $rule->getComment();
            }
        }
    }
    
    private function get()
    {
        return $this->doc;
    }
    
}

/* End of file Doc.php */
/* Location: ./Restserver/Output/Doc.php */
