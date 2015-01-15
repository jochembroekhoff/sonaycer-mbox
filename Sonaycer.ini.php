<?php

/**
 * Sonaycer.ini file viewer
 */

$ini_content = parse_ini_file(dirname(__FILE__).'/Sonaycer.ini', true);
echo '<pre>';
print_r($ini_content);
echo '</pre>';