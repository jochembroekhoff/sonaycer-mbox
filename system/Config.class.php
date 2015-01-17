<?php if(!defined('SONAYCER'))exit;

/**
 * Config class.
 * Load the Sonaycer.ini file in an array with <b>parse_ini_file()</b>
 * 
 * @package Sonaycer
 * @subpackage Config
 */

class Config{
    private $file;
    private $parsed_ini_file;
    
    function __construct() {
        $this->file = dirname(__FILE__).'/../Sonaycer.ini';
        $this->parsed_ini_file = parse_ini_file($this->file, true);
    }
    /**
     * Get a configuration item from Sonaycer.ini
     * @param string $section Section of Sonaycer.ini
     * @param sting $key Key of the Section
     * @return string
     */
    function getItem($section='', $key='') {
        if(!empty($section) and !empty($key)){
            return $this->parsed_ini_file[$section][$key];
        } else {
            return '';
        }
    }
    /**
     * Get a whole configuration section.
     * @param string $section Sectoion of Sonaycer.ini
     * @return array
     */
    function getSection($section='') {
        if(!empty($section)) {
            return $this->parsed_ini_file[$section];
        } else {
            return '';
        }
    }
}