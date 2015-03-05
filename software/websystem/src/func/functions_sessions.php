<?php
/* This File handles all login/logout related stuff */

class sessions {
    /* Class var declaration */
    var $userLevel;
    var $userName;
    var $userId;
    var $regId;

    /* Error handling vars */
    var $error_login_not_found;
    var $error_login_data_not_entered;
    var $error_createNewUser;

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
        $_SESSION["ri"] ? $this->regId=$_SESSION["ri"] : $this->regId=false;
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

        //Set lastseen in acc_db
        $query = $db->query("UPDATE `users` SET `lastseen`='".date("Y-m-d H:i:s", time())."' WHERE `userid`='".$result->{"userid"}."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }

        //Set session vars
        $_SESSION["ul"] = $result->{"userlevel"};
        $_SESSION["un"] = $result->{"nickname"};
        $_SESSION["ui"] = $result->{"userid"};
        $_SESSION["ri"] = $result->{"regid"};

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

    /* getUserLevelText()
     *
     * This function returns userLevel as String
     */
    public function getUserLevelText() {
        switch($this->userLevel) {
            case 0: return "Not logged in"; break;
            case 1: return "User"; break;
            case 2: return "Moderator"; break;
            case 3: return "Admin"; break;
            default: return "Unknown!"; break;
        }
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

    /* getRegId()
     *
     * Getter for $this->regId
     */
    public function getRegId() {
        return $this->regId;
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


    /* generateUserList()
     *
     * This function generates a table bases list of all currently registered users
     *
     * @param $serach_field The database-field the serach query is for
     * @param $search_value The value to serach for in the specified field
     */
    public function genreateUserList($search_field, $search_value) {
        //Gain db access
        global $db;

        //Build search-argument if necessary
        if(!$search_value) { $search_field=false; }
        switch($search_field) {
            case "nickname": $sqlSearch = "`nickname` LIKE '%".$db->escape($search_value)."%'"; break;
            case "regid": $sqlSearch = "`regid` LIKE '%".$db->escape($search_value)."%'"; break;
            case "userid": $sqlSearch = "`userid` LIKE '%".$db->escape($search_value)."%'"; break;
            default: $sqlSearch = "1=1"; break;
        }

        //Get users from database
        $query_users = $db->query("SELECT `userid`, `regid`, `userlevel`, `nickname` FROM `users` WHERE ".$sqlSearch." ORDER BY `nickname` ASC");
        if($db->isError()) { die($db->isError()); }

        //Get amount of bindings for user
        $query_bindingcount = $db->query("SELECT `userid`, COUNT(*) FROM `bindings` GROUP BY `userid` ORDER BY `userid` ASC");
        if($db->isError()) { die($db->isError()); }

        //Print serach-form
        if(!$search_field) { $optionSelected = "callsign"; }
        else { $optionSelected = $search_field; }
        ?>
        <form method="POST" id="usersSearchForm" action="<?=domain?>index.php?p=users" style="margin-bottom: 2px; magin-top: 5px;">
            <input type="hidden" name="usersSearchForm_reset" id=usersSearchForm_reset" value=""/>
            <table>
                <tr>
                    <td style="text-align: right; font-size: 12px;">Search:</td>
                    <td style="text-align: left;">
                        <select name="usersSearchForm_field" required>
                            <option value="nickname" <?php if($optionSelected=="nickname") echo "selected"; ?>>Nickname</option>
                            <option value="regid" <?php if($optionSelected=="regid") echo "selected"; ?>>Reg-ID</option>
                            <option value="userid" <?php if($optionSelected=="userid") echo "selected"; ?>>User-ID</option>
                        </select>
                        <input type="text" name="usersSearchForm_value" value="<?=$search_value?>" placeholder="String to search for" style="width: 200px;"/>
                        <input type="submit" value="Submit"/>
                    </td>
                </tr>
            </table>
        </form>
        <?php

        //Check if users are present
        if(mysqli_num_rows($query_users)<1) {
            ?>There are currently no users in the database! <a href="#" onclick="spawnUserCreationForm()">Register one now!</a><?php
            return true;
        }

        //Generate array from user-bindings
        $userBindings = array();
        while($row = mysqli_fetch_object($query_bindingcount)) {
            $userBindings[$row->{"userid"}] = $row->{"COUNT(*)"};
        }

        //Output table with all users
        ?>
        <div class="userlist_wrapper" id="userlist_wrapper">
        <table class="gptable" style="margin-left: 0px; min-width: 450px;">
            <tr>
                <td class="gptable_head">Nickname</td>
                <td class="gptable_head">BC</td>
                <td class="gptable_head">UID</td>
                <td class="gptable_head">RID</td>
                <td class="gptable_head">Rights</td>
                <td class="gptable_head"></td>
            </tr>
        <?php

        $row_color = "even";
        while($row = mysqli_fetch_object($query_users)) {
            if($row_color == "even") { $row_color = "odd"; }
            else { $row_color = "even"; }
            ?>
            <tr class="gptable_<?=$row_color?>">
                <td><?=$row->{"nickname"}?></td>
                <td>
                    <?php
                        if($userBindings[$row->{"userid"}]) {
                            echo $userBindings[$row->{"userid"}];
                        } else {
                            echo "0";
                        }
                    ?>
                </td>
                <td><?=$row->{"userid"}?></td>
                <td><?=$row->{"regid"}?></td>
                <td>
                    <?php
                        switch($row->{"userlevel"}) {
                            case 1: echo "User"; break;
                            case 2: echo "Mod"; break;
                            case 3: echo "Admin"; break;
                            default: echo "Error!"; break;
                        }
                    ?>
                </td>
                <td style="vertical-align: middle; width:15px;"><a href="#" onclick="deleteUser(<?=$row->{"userid"}?>)" title="Delete User"><img src="<?=domain.dir_img?>trashbin.png" alt="X"/></a></td>
            </tr>
            <?php
        }

        ?>
        </table></div>
        <i>BC: BindingCount&nbsp;&nbsp;-&nbsp;&nbsp;UID: UserID&nbsp;&nbsp;-&nbsp;&nbsp;RID: RegID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="#" onclick="spawnNewUserForm()">Create new User</a>
        <?php

        return true;
    }

    /* newUserFormSubmitted()
     *
     * This function checks if the newUserForm was submitted
     *
     * @return TRUE Form was submitteed
     * @return FASLE Form was NOT submitted
     */
    public function newUserFormSubmitted() {
        //Check if form was submitted
        if($_POST["newUserForm_submitted"]) {
            return true;
        }

        return false;
    }

    /* createNewUser()
     *
     * This function tries to create a new user
     *
     * @param $nickname The given nickname
     * @param $regid The registration ID
     * @param $userlevel The desired userlevel as int
     * @param $password (Optional) A password for the user
     * @param $password_rep (Optional) The password repetition
     *
     * @return TRUE Success, user created
     * @return FALSE Error, user NOT created
     */
    public function createNewUser($nickname, $regid, $userlevel, $password, $password_rep) {
        //Check input
        if(!$nickname || !$regid || !$userlevel) { return false; }
        if(!is_numeric($regid) || !is_numeric($userlevel) || $userlevel<1 || $userlevel>3) { return false; }

        //Gain db access
        global $db;

        //Check if username already exist
        $query_checkNickname = $db->query("SELECT `userid` FROM `users` WHERE `nickname`='".$db->escape($nickname)."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }
        if(mysqli_fetch_object($query_checkNickname)->{"userid"}) { return false; }

        //Hash password if present
        if($password) {
            //Check if passwords are valid
            if($password!=$password_rep || strlen($password)>60 || strlen($password)<4) { return false; }

            //Hash password
            $password = self::hashPassword($password);
        } else {
            //Check if userlevel requires password
            if($userlevel>1) { return false; }
            $password="";
        }

        //Create new database entry for user
        $db->query("INSERT INTO `users` (`regid`, `userlevel`, `nickname`, `password`) VALUES
                    ('".$db->escape($regid)."',
                    '".$db->escape($userlevel)."',
                    '".$db->escape($nickname)."',
                    '".$db->escape($password)."')");
        if($db->isError()) { die($db->isError()); }

        //Add log
        global $core, $sessions;
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Created new user. Set nickname to '".$nickname."', regid to '".$regid."' and userlevel to '".$userlevel."'");

        //User created if code here is reached
        return true;

    }

    /* deleteUserFormSubmitted()
     *
     * This form checks if the deleteUserForm was submitted
     *
     * @return TRUE Form was submitted
     * @return FALSE Form was NOT submitted
     */
    public function deleteUserFormSubmitted() {
        //Check form
        if($_POST["deleteUserForm_submitted"]) { return true; }
        return false;
    }

    /* deleteUser()
     *
     * This function deletes the given user and all associated bindings
     *
     * @param $userid The UID of the user to delete
     *
     * @param TRUE Success, user was deleted
     * @param FALSE Error, user was not deleted
     */
    public function deleteUser($userid) {
        //Check input
        if(!$userid || !is_numeric($userid) || $userid<1) { return false; }

        //Gain db access
        global $db;

        //Delete all associated bindings
        $db->query("DELETE FROM `bindings` WHERE `userid`='".$db->escape($userid)."'");
        if($db->isError()) { die($db->isError()); }

        //Add log
        global $core, $sessions;
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Deleted all bindings associated with user with UID ".$userid.".");

        //Delete user
        $db->query("DELETE FROM `users` WHERE `userid`='".$db->escape($userid)."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }

        //Add log
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Deleted user with UID ".$userid.".");

        return true;
    }

    /* findUser()
     *
     * This function returns all users matching the given pattern
     *
     * @param $regid The given reg-id
     * @param $nickname The given nickname
     * @param $userid The given userid
     *
     * @return ARRAY containing user-objects
     */
    public function findUser($regid, $nickname, $userid) {
        //Check input
        if(!$regid && !$nickname && !$userid) return false;

        //Gain db acess
        global $db;

        //Query database
        $searchFor = "";
        if($regid) { $searchFor .= "`regid`='".$db->escape($regid)."' OR "; }
        if($nickname) { $searchFor .= "`nickname` LIKE '%".$db->escape($nickname)."%' OR "; }
        if($userid) { $searchFor .= "`userid`='".$db->escape($userid)."' OR "; }
        $searchFor .= "TRUE=FALSE";
        $query = $db->query("SELECT `userid`, `regid`, `nickname` FROM `users` WHERE ".$searchFor);
        if($db->isError()) { die($db->isError()); }

        //Generate user-array
        $users = array();
        while($row = mysqli_fetch_object($query)) {
            $users[$row->{"userid"}] = $row;
        }

        return $users;
    }
}

?>