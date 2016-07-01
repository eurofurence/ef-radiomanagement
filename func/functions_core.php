<?php
/**
 * Created by Niels Gandraß.
 * Copyright (C) 2015
 */

class core {
    /* Internal Variables */
    private $settings;
    private $userLevel;

    /* __construct pulls settings from DB */
    function __construct() {
        $this->settings = self::pullSettings();

        //Check for offline mode
        if(!self::getSetting("online"))
            die("<h1>Website im Wartungsmodus!</h1>");

        //Check User's Access-Level
        $this->userLevel = 0;

        //Define domain var
        define(domain, self::getDomain());
    }

    /* pullSettings()
     *
     * This function pulls the settings from the database
     *
     * Return:
     *   Object -> Success, settings returned as object
     *   FALSE -> Error
     */
    private function pullSettings() {
        //Get current db connection
        global $db;

        //Query Database
        $query = $db->query("SELECT * FROM `settings` LIMIT 1");
        if($db->isError()) {
            return false;
        }

        return mysqli_fetch_object($query);
    }

    /* getDomain()
     *
     * This function returns the current domain (e.g. http://www.localhorst.org/dollerordner)
     */
    private function getDomain() {
        $result = "http"; //Domain base

        //Check for HTTPS
        if ($_SERVER["HTTPS"] == "on") {$result .= "s";}

        //Always the same
        $result .= "://";

        //Check for port!=80
        if ($_SERVER["SERVER_PORT"] != "80") {
            $result .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/";
        } else {
            $result .= $_SERVER["SERVER_NAME"]."/";
        }

        return $result;
    }

    /* getSetting()
     *
     * This function returns the desired setting value from the DB
     *
     * Input:
     *   $setting -> Settings db-data-field
     *
     * Return:
     *   String/Int/... -> Success, setting returned
     *   FALSE -> Setting not defined
     */
    public function getSetting($setting) {
        //Check if setting is defined, otherwise return FALSE
        if(isset($this->settings->{$setting})) {
            return $this->settings->{$setting};
        } else { return false; }
    }

    /* addLog()
     *
     * This function adds a new log to the database
     *
     * @param triggered_by The user that triggered the log
     * @param $value The logs-value
     *
     * @return TRUE Log added
     * @return FALSE Error, log NOT added
     */
    public function addLog($triggered_by, $value) {
        //Check input
        if(!$triggered_by || !$value) { return false; }

        //Gain db access
        global $db;

        //Insert new log into database
        $db->query("INSERT INTO `log` (`datetime`, `triggered_by`, `value`)
        VALUES ('".$db->escape(date("Y-m-d H:i:s"))."', '".$db->escape($triggered_by)."', '".$db->escape($value)."')");
        if($db->isError()) { die($db->isError()); }

        return true;
    }

}

?>