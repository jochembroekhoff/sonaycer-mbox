<?php
/*
 * Sonaycer basis AJAX functions
 */

//inculde _base.php for the SONAYCER_BASE constant.
include_once dirname(__FILE__).'/../_base.php';

/**
 * Echo a JSON string (from array)
 * @param array $data
 * @return string
 */
function out($data, $echo = true) {
    if ($echo) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    return json_encode($data);
}