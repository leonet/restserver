<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 * ------------------------------------------------------
 *  Defines usefull constants
 * ------------------------------------------------------
 */
const RESTSERVER_VERSION = '3.0.0';
const RESTSERVER_BASEPATH = __DIR__;

// Defines Classes for loading
static $_classes = array();

// Defines Restserver Classes to load
$_core_classes = array(
    // Defines System Core Classes
    'Core/Config',
    'Core/Server',
    'Core/Rule',
    'Core/Validation',
    'Core/Input',
    'Core/Output',
    // Defines System Input Classes
    'Input/Data',
    'Input/Filter',
    'Input/Limit',
    'Input/Page',
    'Input/Sorter',
    'Input/Start',
    // Defines System Output Classes
    'Output/Cross',
    'Output/Doc',
    'Output/Har',
    'Output/Response',
    // Defines System Log Classes
    'Log/Model'
);

/*
 * ------------------------------------------------------
 *  Load the System Core Classes
 * ------------------------------------------------------
 */
if (! empty($_core_classes)) {
    foreach ($_core_classes as $class_path) {
        if (file_exists(RESTSERVER_BASEPATH.'/'.$class_path.'.php')) {
            // Require class path
            require_once(RESTSERVER_BASEPATH.'/'.$class_path.'.php');

            // Define class name
            $class_name = explode('/', $class_path)[1];

            var_dump(file_exists(RESTSERVER_BASEPATH.'/'.$class_path.'.php'));
            var_dump(RESTSERVER_BASEPATH.'/'.$class_path.'.php');

            // Load the class
            $_classes[$class_name] = new $class_name();
        } else {
            // Returns Class loader error
            http_response_code(503);
            echo 'Unable to locate the specified class: '.$class_path.'.php';
            exit(5);
        }
    }

    // Returns Restserver Instance
    return $_classes[$class_name];
}

var_dump($_classes);


/*
 * ------------------------------------------------------
 *  Load the System Log Classe
 * ------------------------------------------------------
 */
if (file_exists(RESTSERVER_BASEPATH.'/Log/Model.php')) {
    require_once(RESTSERVER_BASEPATH.'/Log/Model.php');
}
