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
    echo json_encode($data);
    return json_encode($data);
}