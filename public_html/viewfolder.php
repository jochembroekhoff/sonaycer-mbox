<?php
define('SONAYCER',true);
include dirname(__FILE__).'/_base.php';
require_once SONAYCER_BASE.'/system/Config.class.php';
$Config = new Config();

if (isset($_REQUEST['folderid'])) {
    $folder = (string)$_REQUEST['folderid'];
} else {
    header('location:http://' . $Config->getItem('site', 'host') . '/' . $Config->getItem('site', 'path') . '/index');
}
?>
<form method=post action="../index"><input type="hidden" name="action" value="folder">
    <input type="hidden" name="folder" value="<?=$folder?>"/></form>
<script>document.forms[0].submit();</script>
<noscript><meta http-equiv="refresh" content="0;URL=http://<?=$Config->getItem('site', 'host') . '/' . $Config->getItem('site', 'path') . '/noscript'?>"/></noscript>