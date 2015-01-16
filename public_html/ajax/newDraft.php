<?php
include dirname(__FILE__) . '/_ajax.php';
/**
 * Create a new draft.
 * @return array draftId => 'hash/id'
 */
function newDraft() {
    return array();
}

//Call the newDraft function to complete the AJAX request
out(newDraft());