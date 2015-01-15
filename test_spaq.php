<?php

/**
 * Test a .spaq file
 */

require_once dirname(__FILE__).'/system/SPAQ.class.php';
$SPAQ = new SPAQ();

$file = dirname(__FILE__).'/example.spaq';
$SPAQ->load($file);

//create an executable sPaq
$SPAQ->set_type('plugin');

echo '<pre>'; print_r($SPAQ->get_about_all());