<?php

/**
 * Create a new .spaq file.
 */

$file = dirname(__FILE__).'/example.spaq';

require_once dirname(__FILE__).'/system/SPAQ.class.php';

$SPAQ = new SPAQ();
$SPAQ->load($file, true);