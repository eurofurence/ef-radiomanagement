<?php
/* This File handles all login/logout related stuff */

class sessions {
    /* Class var declaration */
    var $userLevel;
    var $userName;
    var $userId;

    /* Error handling vars */
    var $error_login_not_found;
    var $error_login_data_not_entered;

    /* State vars */
    var $loginSuccessful = false;

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

    /* login()
     *
     * This function tries to login an user
     *
     * @param $nickname The given nickname
     * @param $regid The given regid
     * @param $password The optional password (Only for mods and admins)
     *
     * @return TRUE Login successful
     * @return FALSE Login FAILED - See error vars
     */
    public function login($nickname, $regid, $password) {
        //Gain db-access
        global $db;

        //Check for given values
        if(!$nickname || !$regid) {
            $this->error_login_data_not_entered = true;
            return false;
        }

        //Check for given combination in Database
        $query = $db->query("SELECT `userid`, `regid`, `userlevel`, `nickname`, `password` FROM `users` WHERE `regid`='".$db->escape($regid)."' AND `nickname`='".$db->escape($nickname)."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }
        $result = mysqli_fetch_object($query);

        //Check db-data
        if(!$result->{"userid"}) {
            $this->error_login_not_found = true;
            return false;
        }

        //Check if password is required
        if($result->{"userlevel"} > 1) {
            //User is higher then normal user => Check 4 password
            if($result->{"password"} != self::hashPassword($db->escape($password))) {
                $this->error_login_not_found = true;
                return false;
            }
        }

        //Set session vars
        $_SESSION["ul"] = $result->{"userlevel"};
        $_SESSION["un"] = $result->{"nickname"};
        $_SESSION["ui"] = $result->{"userid"};

        //Tell system, that we are now logged into Skynet(tm)
        $this->loginSuccessful = true;

        return true;
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

    /* loginSuccessful()
     *
     * Getter for $this->loginSuccessful
     * (True if login completed)
     */
    public function loginSuccessful() {
        return $this->loginSuccessful;
    }

    /* loginFormSubmitted()
     *
     * This form checks if the login-form was submitted
     *
     * @return TRUE Login form was submitted
     * @return FALSE Login form was NOT submitted
     */
    public function loginFormSubmitted() {
        //Check hidden-field
        if($_POST["loginform_submitted"]=="true") {
            return true;
        }

        return false;
    }

}

?>