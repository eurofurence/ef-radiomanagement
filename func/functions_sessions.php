<?php
/* This File handles all login/logout related stuff */

class sessions {
    /* Class var declaration */
    var $userLevel;
    var $userName;
    var $userId;

    /* Error handling vars */
    var $error_pass_unequal;
    var $error_pass_unsuited;

    /* __construct()
     *
     * Constructor for sessions
     */
    function __construct() {
        session_start();
        session_regenerate_id();

        //Recover session information
        $_SESSION["ul"] ? $this->userLevel=$_SESSION["ul"] : $this->userLevel=0;
        $_SESSION["un"] ? $this->userName=$_SESSION["un"] : $this->userName=false;
        $_SESSION["ui"] ? $this->userId=$_SESSION["ui"] : $this->userId=false;
    }

    /* logout()
     *
     * This function destroys the user session
     */
    public function logout() {
        session_destroy();
        return true;
    }

    /* hashPassword()
     *
     * This function calculates a DB pass hash from the input
     *
     * @param $input The unhashed password
     * @return STRING The hash
     */
    public function hashPassword($input) {
        return hash("sha512", $input);
    }

    /* getUserLevel()
     *
     * Getter for $this->userLevel
     */
    public function getUserLevel() {
        return $this->userLevel;
    }

    /* getUserName()
     *
     * Getter for $this->userName
     */
    public function getUserName() {
        return $this->userName;
    }

    /* getUserId()
     *
     * Getter for $this->userId
     */
    public function getUserId() {
        return $this->userId;
    }

}

?>