<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['allow_methods'] = array('GET', 'POST', 'PUT', 'DELETE');
$config['allow_headers'] = array('authorization', 'content-type', 'x-requested-with');
$config['allow_credentials'] = FALSE;
$config['allow_origin'] = FALSE;
$config['force_https'] = FALSE;
$config['ajax_only'] = FALSE;
$config['auth_http'] = FALSE;
$config['debug'] = FALSE;
$config['log'] = FALSE;
$config['log_driver'] = 'file';
$config['log_db_name'] = 'rest'; // Database only
$config['log_db_table'] = 'log'; // Database only
$config['log_file_path'] = ''; // File only
$config['log_file_name'] = ''; // File only
$config['log_extra'] = FALSE;
