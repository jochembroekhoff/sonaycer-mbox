<?php
/**
 * 
 * @author Jochem
 */
class SPAQ_Plugin {
    /**
     * sPaq class. It's SPAQ_Plugin's parent.
     * @var SPAQ
     */
    private $SPAQ;
    /**
     * Last excepted error. [time] = unixtime of exception<br/>
     *                      [msg] &nbsp;= message of thrower
     * @var array
     */
    private $last_error = array('time','msg');
    
    /**
     * Constructor for a sPaq Plugin
     * @param SPAQ $SPAQ
     */
    function __construct($SPAQ) {
        $this->SPAQ = $SPAQ;
    }
    /**
     * Install the current .sPaq file. If there are installation instructions
     * available, the plugin is installed following this instructions. Other-
     * wise, if they aren't there, all files of the .sPaq file are copied to the
     * plugin dir.
     * @return boolean True  = succesfully installed<br/>
     *                 False = an error excepted. (You can see wich error with 
     *                         <code>->last_error()</code>
     */
    function install() {
        //$this->i
    }
    /**
     * Uninstall
     * @param boolean $keep_cache_and_other_stuff
     */
    function uninstall($keep_cache_and_other_stuff = false) {
        
    }
    /**
     * Get the last excepted error.
     * @return array [time] = unixtime of exception<br/>
     *               [msg] &nbsp;= the message of the thrower
     */
    function last_error() {
        return $this->last_error;
    }
}