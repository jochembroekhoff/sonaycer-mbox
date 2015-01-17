<?php if(!defined('SONAYCER'))exit;

/**
 * The real base of Sonaycer
 * @author Jochem Broekhoff
 */
class Sonaycer{
    /**
     * Config class
     * @var Config
     */
    public $config;
    /**
     * Language class
     * @var Language
     */
    public $lang;
    
    function __construct(){
        //require_once dirname(__FILE__).'/Config.class.php';
        $this->config = new Config();
        $this->lang = new Language($this->config->getSection('language'));
    }
    /**
     * Get te URL of the <code>$file</code> in /min/f=
     * @param type $file
     */
    function minify($file) {
        return 'http://' . $this->config->getItem('site', 'host') . '/' .
               $this->config->getItem('site', 'path') . '/min/f=' . 
               $this->config->getItem('site', 'path') . $file;
    }
}
