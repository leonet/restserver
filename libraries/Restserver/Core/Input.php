<?php
namespace Restserver\Core;

defined('BASEPATH') or exit ('No direct script access allowed');

class Input
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
    }

    /**
     * Retourne la méthode
     * @return string la méthode
     */
    public function getMethod()
    {
        $method = $this->CI->input->server('REQUEST_METHOD');
        return (!empty($method)) ? strtolower($method) : '';
    }

    /**
     * Retourne l'URL
     * @return string
     */
    public function getUrl()
    {
        $url = current_url();
        return (!empty($url)) ? $url : '';
    }

    /**
     * Retourne l'adresse IP
     * @return string
     */
    public function getIp()
    {
        $ip = $this->CI->input->ip_address();
        return (!empty($ip)) ? $ip : '';
    }

    /**
     * Retourne la liste des en-têtes
     * @return array
     */
    public function getHeaders()
    {
        $headers = $this->CI->input->request_headers(true);
        return (!empty($headers)) ? $headers : array();
    }

    /**
     * Si le protocol est de type HTTPS
     * @return boolean
     */
    public function isSsl()
    {
        return ($this->CI->input->server('HTTPS') == 'on');
    }

    public function isAjaxRequest()
    {
        return $this->CI->input->is_ajax_request();
    }

    /**
     * Retourne toutes les données entrantes
     * @return array
     */
    private function getData()
    {
        $method = $this->getMethod();
        $get    = null;
        $post   = null;
        $put    = null;
        $patch  = null;
        $delete = null;

        switch ($method) {
            case 'get':
                $_get = $this->CI->input->get();
                $_uri = $this->CI->uri->ruri_to_assoc();

                // Fusionne les données Get + Uri
                $get  = array_merge((array) $_get, (array) $_uri);
                break;
            case 'post':
                $post = $this->CI->input->post();

                // Si les données entrantes sont un POST normal
                if (!empty($post)) {
                    break;
                }
            case 'patch':
            case 'put':
            case 'delete':
                // Récupère les données entrantes
                $input = file_get_contents('php://input');

                // Si les données sont en JSON
                ${$method} = @json_decode($input, true);

                // Si les données sont en HTTP
                if (empty(${$method})) {
                    parse_str($input, ${$method});
                }
        }

        // Renvoi les données entrantes
        return array(
            'get'    => (is_array($get))    ? $get    : array(),
            'post'   => (is_array($post))   ? $post   : array(),
            'patch'  => (is_array($patch))  ? $patch  : array(),
            'put'    => (is_array($put))    ? $put    : array(),
            'delete' => (is_array($delete)) ? $delete : array()
        );
    }
}

/* End of file Input.php */
/* Location: ./Restserver/Core/Input.php */
