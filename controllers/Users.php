<?php
/**
 * REST Full server for Codeigniter 3
 * 
 * @author Yoann Vanitou <yvanitou@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link   https://github.com/maltyxx/restserver
 * @since   Version 2.1.0
 * @filesource
 *
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends Restserver_controller
{

    public function __construct()
    {
        parent::__construct();
        
        $config = array(
            array(
                'field' => 'id',
                'type'  => 'input',
                'rules' => array(
                    array('required_put',    array($this->restserver, 'required_put')),
                    array('required_delete', array($this->restserver, 'required_delete')),
                    'is_natural_no_zero'
                ),
                'comment' =>
                    "Input: id".PHP_EOL.
                    "Type: integer".PHP_EOL.
                    "Requis: PUT, DELETTE"
            ),
            array(
                'field' => 'lastname',
                'type'  => 'input',
                'alias' => 'user.lastname|famille.father.name',
                'label' => 'Nom',
                'rules' => array(
                    array('required_post', array($this->restserver, 'required_post')),
                    array('required_put',  array($this->restserver, 'required_put')),
                    'alpha',
                    'min_length[2]',
                    'max_length[20]'
                ),
                'comment' =>
                    "Input: lastname".PHP_EOL.
                    "Label: Nom de famille".PHP_EOL.
                    "Type: string (min 2, max 20 caractères)".PHP_EOL.
                    "Requis: POST, PUT"
            ),
            array(
                'field' => 'firstname',
                'type'  => 'input',
                'label' => 'Prénom',
                'rules' => 'alpha|min_length[2]|max_length[20]',
                'comment' =>
                    "Input: firstname".PHP_EOL.
                    "Label: Prénom".PHP_EOL.
                    "Type: string (min 2, max 20 caractères)"
            ),
            array(
                'field' => 'name',
                'type'  => 'filters',
                'rules' => 'alpha|min_length[2]|max_length[20]'
            )
        );
        
        $this->restserver->set_rules($config);
    }

    public function post()
    {
        $lastname = $this->restserver->post('lastname');
        
        // ---------- Réponse
        $response = $this->restserver->protocol();
        $response['status'] = true;
        $response['error'] = null;
        $response['value'] = array(
            'id'        => 1,
            'lastname'  => $this->restserver->input('lastname'),
            'firstname' => $this->restserver->input('firstname')
        );
        
        $this->restserver->response($response, 201);
    }
    
    /**
     * Méthode GET
     */
    public function get()
    {
        $id  = $this->restserver->input('id');
        
        if ( ! empty($id)) {
            $response = $this->restserver->protocol();
            $response['status'] = true;
            $response['error'] = null;
            $response['value'] = array(
                'id'        => 1,
                'lastname'  =>'Yoann',
                'firstname' => null
            );
        } else {
            $response = $this->restserver->protocol();
            $response['status'] = true;
            $response['error'] = null;
            $response['value'] = array(
                array(
                    'id'        => 1,
                    'lastname'  =>'Yoann',
                    'firstname' => null
                ),
                 array(
                    'id'        => 2,
                    'lastname'  =>'Jhon',
                    'firstname' => null
                )
            );
        }
    }
        
    /**
     * Méthode PUT
     */
    public function put()
    {
        $alias = $this->restserver->alias();
        
        $response = $this->restserver->protocol();
        $response['status'] = true;
        $response['error'] = null;
        $response['value'] = array(
            'id'        => $this->restserver->input('id'),
            'lastname'  => ($this->restserver->input('lastname') === $alias['user']['lastname']) ? $alias['user']['lastname'] : null,
            'firstname' => $this->restserver->input('firstname')
        );
        
        $this->restserver->response($response, 200);
    }
    
    /**
     * Méthode DELETE
     */
    public function delete()
    {
        $this->restserver->response();
    }

}

/* End of file Origami_test.php */
/* Location: ./application/controllers/Origami_test.php */
