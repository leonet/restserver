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

class Test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Dépendances
        $this->load->library('unit_test');

        $package_path = FCPATH.'vendor/maltyxx/restclient';

        // Chargement de la librairie
        $this->load->add_package_path($package_path)
                ->library('restclient')
                ->remove_package_path($package_path);

        $this->load->helper('url');
        /*
          $this->test1();
          $this->test2();
          $this->test3();
          $this->test4();
          $this->test5();
          $this->test6();
          $this->test7();
          $this->test8();
          $this->test9();
          $this->test10();
          $this->test11();*/
          $this->test12();

        $this->output->set_output($this->unit->report());
        $this->output->enable_profiler();
    }

    public function test1()
    {
        $response = $this->restclient->post(site_url('users'), array(
            'lastname' => 'John'
        ));

        $result = $response['status'] === true && $response['value']['lastname'] === 'John';

        //$this->restclient->debug();

        $this->unit->run($result, true, "post + send data");
    }

    public function test2()
    {
        $response = $this->restclient->post(site_url('users'));

        $result = $response['status'] === false;

        //$this->restclient->debug();

        $this->unit->run($result, true, "post + send no data");
    }

    public function test3()
    {
        $response = $this->restclient->post(site_url('users'));

        $result = $response['status'] === false;

        //$this->restclient->debug();

        $this->unit->run($result, true, "post + send no data");
    }

    public function test4()
    {
        $response = $this->restclient->post(site_url('users'), array(
            'doe' => 'John'
        ));

        //$this->restclient->debug();

        $result = $response['status'] === false;

        $this->unit->run($result, true, "post + other data");
    }

    public function test5()
    {
        $response = $this->restclient->post(site_url('users'), array(
            'lastname' => '---------------------'
        ));

        $result = $response['status'] === false;

        //$this->restclient->debug();

        $this->unit->run($result, true, "post + data validation");
    }

    public function test6()
    {
        $response = $this->restclient->post(site_url('users'), array(
            'lastname' => 'a'
        ));

        $result = $response['status'] === false;

        //$this->restclient->debug();

        $this->unit->run($result, true, "post + data validation");
    }

    public function test7()
    {
        $response = $this->restclient->post(site_url('users'), array(
            'lastname' => 'abcdefghijklmnopqrstuvwxyz'
        ));

        $result = $response['status'] === false;

        //$this->restclient->debug();

        $this->unit->run($result, true, "post + data validation");
    }

    public function test8()
    {
        $response = $this->restclient->put(site_url('users'), array(
            'id'       => 1,
            'lastname' => 'Yoann'
        ));

        $result = $response['status'] === true && $response['value']['lastname'] === 'Yoann';

        //$this->restclient->debug();

        $this->unit->run($result, true, "put + send data");
    }

    public function test9()
    {
        $response = $this->restclient->put(site_url('users'), array(
            'doe' => 'John'
        ));

        $result = $response['status'] === false;

        //$this->restclient->debug();

        $this->unit->run($result, true, "put + data validation");
    }

    public function test10()
    {
        $response = $this->restclient->get(site_url('users'), array(
            'id' => 1
        ));

        $result = $response['status'] === true && $response['value']['id'] == 1;

        //$this->restclient->debug();

        $this->unit->run($result, true, "get by GET id");
    }

    public function test11()
    {
        $response = $this->restclient->get(site_url('users/1'));

        $result = $response['status'] === true && $response['value']['id'] == 1;

        //$this->restclient->debug();

        $this->unit->run($result, true, "get by GET id");
    }

    public function test12()
    {
        $response = $this->restclient->get(site_url('users'), array(
            'id'     => 1,
            'filters' => json_encode(array(
                array(
                    'property' => 'name',
                    'value'    => 'yoann'
                ),
                array(
                    'property' => 'name',
                    'value'    => 'Jhon'
                )
            )),
            'sorters'   => json_encode(array(
                array(
                    'property'  => 'name',
                    'direction' => 'ASC'
                )
            )),
            'page'    => 1,
            'start'   => 0,
            'limit'   => 10
        ));

        $result = $response['status'] === true && $response['value'][0]['id'] == 1;

        $this->restclient->debug();

        $this->unit->run($result, true, "get by GET id");
    }

}

/* End of file Origami_test.php */
/* Location: ./application/controllers/Origami_test.php */
