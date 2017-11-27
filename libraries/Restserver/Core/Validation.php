<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Validation
{
    protected $CI;

    protected $data;

    protected $rules;

    protected $response;

    function __construct(\Restserver\Output\Response $response)
    {
        $this->CI =& get_instance();
        $this->CI->load->library('form_validation');

        $this->response =& $response;
    }

    public function setData(\Restserver\Input\Data &$data)
    {
        $this->data = $data;
    }

    public function setRules(\Restserver\Input\Rules &$rules)
    {
        $this->rules = $rules;
    }

    public function run()
    {
        if (!empty($rules)) {
            // Vérification des données entrantes
            $this->CI->form_validation->reset_validation();
            $this->CI->form_validation->set_data($this->data->get());
            $this->CI->form_validation->set_rules($this->rules->get());
            $this->CI->form_validation->set_error_delimiters('', '');

            // Si le validateur a rencontré une ou plusieurs erreurs
            if ($this->CI->form_validation->run() === false) {
                $errors = $this->CI->form_validation->error_array();

                $this->response->set(array(
                    'status' => false,
                    'error'  => (!empty($errors)) ? $errors : 'Unsupported data validation'
                ), 400);

                return false;
            }
        }

        return true;
    }
}

/* End of file Validation.php */
/* Location: ./Restserver/Core/Validation.php */
