<?php
include dirname(__FILE__) . '/_ajax.php';
/**
 * Get the language! Returns <code>{"error":true}</code> if the requested
 *                   language doesn't exists.
 *                   If no language is set, the default language is taken from
 *                   Sonaycer.ini.
 * @param string $langcode Language code.
 * @return array Language array.
 */
function getLanguage($langcode) {
    if (is_string($langcode)) {
        $file = SONAYCER_BASE . '/system/lang/' . $langcode . '.json';
        if (file_exists($file)) {
            $languageFile = file_get_contents($file);
            return json_decode($languageFile, true);
        } else {
            return array('error'=>true);
        }
    } else {
        return array('error'=>true);
    }
}

//Get the language code
$settings = parse_ini_file(SONAYCER_BASE . '/Sonaycer.ini', true);
$defaultlangcode = $settings['language']['default'];
$langcode = isset($_REQUEST['langCode']) 
    ? $_REQUEST['langCode']
    : $defaultlangcode;

//Call the getSesion function to complete the AJAX request
out(getLanguage($langcode));