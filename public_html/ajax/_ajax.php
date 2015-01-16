<?php
/*
 * Sonaycer basis AJAX functions
 */

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