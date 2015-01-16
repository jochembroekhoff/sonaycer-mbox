<?php
include dirname(__FILE__) . '/_ajax.php';
/**
 * Get session information of current user.
 * @return array User information. If <code>signedIn => false</code>, there
 *                is no user signed in.
 */
function getSession() {
    return array(
        'signedIn' => true,
        'token'    => 'exampleToKeN'
        );
}

//Call the getSesion function to complete the AJAX request
out(getSession());