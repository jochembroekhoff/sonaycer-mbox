<?php

/*
 * Boot Sonaycer
 */

define('SONAYCER',true);

/**
 * Autoload a class
 * @param string $classname Name of the class
 */
function __autoload($classname) {
    require_once dirname(__FILE__) . '/' . $classname . '.class.php';
}

/*
 * Set $Sonaycer to the Sonaycer class.
 */
$Sonaycer = new Sonaycer();