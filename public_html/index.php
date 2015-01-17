<?php
include dirname(__FILE__).'/_base.php';
include SONAYCER_BASE.'/system/boot.php';

$Host = $Sonaycer->config->getItem("site", "host");
$Path = $Sonaycer->config->getItem("site", "path");

$extra_action = '';
switch ($_REQUEST['action']) {
    case 'compose':
        $action='compose';
        if (isset($_REQUEST['mailto_data'])) {
            $extra_action = 'data-sonaycer-action-mailto=""';
        }
        break;
    case 'folder':
        $action='folder';
        if (isset($_REQUEST['folder'])) {
            $folder = (string)$_REQUEST['folder'];
            $extra_action = 'data-sonaycer-action-folder="$folder"';
        } else {
            $extra_action = 'data-sonaycer-action-folder="INBOX"';
        }
        break;
    default:
        $action='folderview';
}
?>
<!doctype html>
<html lang="<?=$Sonaycer->lang->getLangCode()?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Sonaycer MBox</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.1/cosmo/bootstrap.min.css"/>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="<?=$Sonaycer->minify('/css/style.css')?>"/>
    </head>
    <body data-sonaycer-host="<?=$Host?>" data-sonaycer-path="<?=$Path?>">
        <div class="container hidden" id="sonaycer-main-container">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sonaycer-navbar">
                        <span class="sr-only">Navigatie omschakelen</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand"><i class="fa fa-card"></i> Sonaycer MBox</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Mail</a></li>
                    <li><a href="#">Contacts</a></li>
                    <li><a href="#">Profile</a></li>
                </ul>
            </nav>
            <div class="row well">
                <div class="col-md-3">
                    <button class="btn btn-primary btn-block" id="sonaycer-compose-button">
                        <i class="fa fa-plus"></i> New message
                    </button>
                    <ul class="nav nav-pills nav-stacked">
                        <li class="header">Folders</li>
                        <li role="presentation" class="active"><a href="#">INBox</a></li>
                        <li role="presentation"><a href="#">Drafts</a></li>
                        <li role="presentation"><a href="#">Trash</a></li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <div id="sonaycer-main-actionbar" class="row">
                        <div class="col-md-8">
                            button for actions is coming here
                        </div>
                        <div class="col-md-4">
                            <div class="input-group pull-right">
                                <input type="text" class="form-control"/>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="sonaycer-main-mails">
                        table with e-mails
                    </div>
                </div>
            </div>
            <div class="row" id="sonaycer-main-language-chooser">
                <div class="col-md-12">
                    <small>
                        Languge: <a href="#">English</a> | <a href="#">Dutch</a></small>
                    </small>
                </div>
            </div>
        </div>
        <div class="container" id="sonaycer-start-container" data-sonaycer-start-action="<?=$action?>" <?=$extra_action?>>
            <div class="row">
                <div class="col-md-3 col-sm-1">&nbsp;</div>
                <div class="col-md-6 col-sm-10" id="sonaycer-start-col">
                    <h1 class="text-center">Sonacyer MBox
                        <br/><small id="sonaycer-start-text"></small>
                    </h1>
                    <div class="loading">&nbsp;</div>
                </div>
                <div class="col-md-3 col-sm-1">&nbsp;</div>
            </div>
        </div>
        <script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script src="<?=$Sonaycer->minify('/js/sonaycer.js')?>"></script>
    </body>
</html>