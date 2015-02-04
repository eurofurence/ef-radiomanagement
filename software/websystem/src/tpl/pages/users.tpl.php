<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
$bindings = new bindings();
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

    <div style="display: none;" id="newUserForm_wrapper">
        <b>Create new user</b>&nbsp;-&nbsp;<a href="#" onclick="despawnNewUserForm()">Cancel</a> <br/>
        <form method="POST" action="<?=domain?>index.php?p=users">
            <table style="font-size: 12px;">
                <tr>
                    <td style="text-align: right; padding-right:3px;">Nickname</td>
                    <td><input type="text" size="20" name="newUserForm_nickname" value="" placeholder="Randomfur42" required/></td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-right:3px;">RegID</td>
                    <td><input type="text" size="1" name="newUserForm_regid" value="" placeholder="1337" required/></td>
                </tr>
                <tr>
                    <td style="text-align: right; padding-right:3px;">Rights</td>
                    <td>
                        <select name="newUserForm_rights" required>
                            <option value="1">1 - User</option>
                            <option value="2">1 - Moderator</option>
                            <option value="3">1 - Administrator</option>
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

<?php require_once(basetpl_footer); ?>