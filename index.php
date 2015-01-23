<?php
/**
 * Created by Niels Gandraß.
 * Copyright (C) 2015
 */

//Include dependencies
require_once("settings.php");
require_once(func_core);
require_once(func_database);
require_once(func_template);
require_once(func_sessions);

//Connect to Database
$db = new database($_SETTINGS["db_ip"], $_SETTINGS["db_port"], $_SETTINGS["db_user"], $_SETTINGS["db_pass"], $_SETTINGS["db_dbase"]);
if($db->isError()) { die($db->isError()); }

//Load core functions
$core = new core();

//Load session functions
$sessions = new sessions();

//Generate Site
$template = new template($_GET["p"]);

//Close Database-connection if site is ready
$db->close();

?>