<?php
include dirname(__FILE__) . '/_ajax.php';
/**
 * Get the language!
 * @return array Language array.
 */
function getSession() {
    return array('hello'=>'hoi');
}

//Call the getSesion function to complete the AJAX request
out(getSession());