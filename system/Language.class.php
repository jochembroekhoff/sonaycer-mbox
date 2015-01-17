<?php if(!defined('SONAYCER'))exit;

/**
 * Language class.
 * Load in a language.
 * 
 * @package Sonaycer
 * @subpackage Language
 */

class Language{
    /**
     * Code of the current language
     * @var string
     */
    private $current_lang_code='en';
    /**
     * Configuration. Got via Sonaycer.class.php.
     * @var array
     */
    private $config;
    
    /**
     * Construct this class.
     * @param array $config Array with configuration
     */
    function __construct($config) {
        $this->current_lang_code = $config['default'];
        $this->config = $config;
    }
    
    /**
     * Get the code of the current language.
     * @return string
     */
    function getLangCode() {
        return $this->current_lang_code;
    }
}