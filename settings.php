<?php
/**
 * Created by Niels Gandraß.
 * Copyright (C) 2015
 */

/* Set Timezone */
date_default_timezone_set('Europe/Berlin');

/* Set default charset for foreign servers */
header('Content-Type: text/html; charset=UTF-8');

/* DEFINES for functions */
define(func_core, "func/functions_core.php");
define(func_database, "func/functions_database.php");
define(func_template, "func/functions_template.php");
define(func_sessions, "func/functions_sessions.php");

/* DEFINES for directories */
define(dir_css, "css/");
define(dir_img, "img/");
define(dir_js, "js/");
define(dir_maintpl, "tpl/");
define(dir_pagetpl, "tpl/pages/");
define(dir_iftpl, "tpl/interfaces/");
define(dir_navis, "tpl/navis/");
define(dir_ext, "ext/");

/* Basic settings */
$_SETTINGS = array (
    "db_ip" => "127.0.0.1", //Database IP
    "db_port" => 3306, //Database Port
    "db_user" => "root", //Database User
    "db_pass" => "x4f889g", //Database Userpass
    "db_dbase" => "rms" //Default Database's name
);

/* Available templates
 *
 * alias       = GET-Parameter value
 * file        = local template file
 * accessLevel = Required Userlevel (e.g. 0=Unlogged, 1=User, 2=Moderator, 3=Administrator)
 */
$_TEMPLATES = array();
$_TEMPLATES[] = array("alias" => "index", "file" => dir_pagetpl."index.tpl.php", "accessLevel" => 0);

?>