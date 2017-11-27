<?php
namespace \Restserver\Input;

class Start
{
    protected $value;

    public function get()
    {
        return $this->value;
    }
    
    public function set($value)
    {
        $this->value = (int)$value;
    }

}
