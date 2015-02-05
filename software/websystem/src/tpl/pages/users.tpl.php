<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
$bindings = new bindings();

//Try to create new user if form was submitted
if($sessions->newUserFormSubmitted()) {
    if(!$sessions->createNewUser($_POST["newUserForm_nickname"],
                                 $_POST["newUserForm_regid"],
                                 $_POST["newUserForm_userlevel"],
                                 $_POST["newUserForm_password"],
                                 $_POST["newUserForm_password_repeat"])) {
        $createNewUserError = true;
    } else {
        $_POST["newUserForm_nickname"]=false;
        $_POST["newUserForm_regid"]=false;
        $_POST["newUserForm_userlevel"]=false;
        $_POST["newUserForm_password"]=false;
        $_POST["newUserForm_password_repeat"]=false;
    }
}

//Delete user if deleteUserForm was submitted
if($sessions->deleteUserFormSubmitted()) {
    if(!$sessions->deleteUser($_POST["deleteUserForm_userid"])) { $deleteUserError=true; }
}
?>

<td class="content_td">
    <script src="<?=domain.dir_js?>users.js"></script>
    <div class="page_title">Users</div>
    <hr class="header_spacer"/>

    <span class="content_block">This page gives you an access to all user related database entries.</span><br/>
    <br/>

    <span class="content_block">
        <div class="page_subtitle">Registered Users</div>
        <hr class="header_spacer"/>
        <span class="content_block">The tabel below contains all listed users and is searchable.<br/>To create a new user use the link below the table!</span><br/>
        <?php $sessions->genreateUserList($_POST["usersSearchForm_field"], $_POST["usersSearchForm_value"]); ?>
    </span><br/>
    <br/>

    <form method="POST" id="deleteUserForm" style="display:none;">
        <input type="hidden" name="deleteUserForm_submitted" value="true"/>
        <input type="hidden" name="deleteUserForm_userid" id="deleteUserForm_userid" value=""/>
    </form>

    <div style="display: none;" id="newUserForm_wrapper">
        <b>Create new user</b>&nbsp;-&nbsp;<a href="#" onclick="despawnNewUserForm()">Cancel</a><br/>
        <?php if($createNewUserError || $deleteUserError) { ?><b style="color: #EE0000;">Error, invalid input!</b><?php } ?>
        <form method="POST" action="<?=domain?>index.php?p=users">
            <input type="hidden" name="newUserForm_submitted" value="true"/>
            <table style="font-size: 12px;">
                <tr>
                    <td style="text-align: right; padding-right:3px;">Nickname</td>
                    <td><input type="text" size="20" name="newUserForm_nickname" value="<?=$_POST["newUserForm_nickname"]?>" placeholder="Randomfur42" required/></td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-right:3px;">RegID</td>
                    <td><input type="text" size="1" name="newUserForm_regid" value="<?=$_POST["newUserForm_regid"]?>" placeholder="1337" required/></td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-right:3px;">Rights</td>
                    <td>
                        <select name="newUserForm_userlevel" required>
                            <option value="1" <?php if($_POST["newUserForm_userlevel"]==1) { echo "selected"; } ?>>1 - User</option>
                            <option value="2" <?php if($_POST["newUserForm_userlevel"]==2) { echo "selected"; } ?>>2 - Moderator</option>
                            <option value="3" <?php if($_POST["newUserForm_userlevel"]==3) { echo "selected"; } ?>>3 - Administrator</option>
                        </select>
                    </td>
                </tr>
                <tr><td></td><td><hr class="header_spacer"></td></tr>
                <tr>
                    <td style="text-align: right; padding-right:3px;">(Opt.) Password</td>
                    <td><input type="password" size="20" name="newUserForm_password" value="" placeholder=""/></td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-right:3px;">Repeat Password</td>
                    <td><input type="password" size="20" name="newUserForm_password_repeat" value="" placeholder=""/></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;"><input type="submit" value="Create User" /></td>
                </tr>
            </table>
        </form>
    </div>
    <br/>
</td>

<?php
    if($createNewUserError) { ?><script type="text/javascript">spawnNewUserForm();</script><?php }
    require_once(basetpl_footer);
?>