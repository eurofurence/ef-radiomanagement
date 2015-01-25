<?php
    //Check if user is still logged in
    if($sessions->getUserId()) {
        $sessions->logout();
    }

    //Check if login-form was submitted
    if($sessions->loginFormSubmitted()) {
        if($sessions->login($_POST["loginform_nickname"], $_POST["loginform_regid"], $_POST["loginform_password"])) {
            header("Location: ".domain."index.php?p=overview");
        }
    }

    if(!$sessions->loginSuccessful()) {
        //Login-Form not submitted or login failed - Print it!
        require_once(basetpl_header);
        ?>
        <div class="title"><a href="<?=domain?>"><img src="<?=domain.dir_img?>title.jpg"/></a></div>
        <br/>
        <div class="login_content_container">
            <div class="page_title">System Login</div>
            <br/>
            <div class="login_data_box" style="display: inline-block; text-align: center;">
                <?php
                    //Check for login errors
                    if($sessions->error_login_not_found) { ?><div class="login_error">Combination not found in database!</div><?php }
                    if($sessions->error_login_data_not_entered) { ?><div class="login_error">Please enter Nickname and Reg-ID!</div><?php }
                ?>
                <form method="POST" action="index.php?p=login">
                    <input type="hidden" name="loginform_submitted" value="true"/>
                    <table>
                        <tr>
                            <td style="text-align: right; font-size: 10pt;">Nickname</td>
                            <td><input type="text" name="loginform_nickname" placeholder="Randomfur" value="<?=$_POST["loginform_nickname"]?>" style="width: 200px;" required/></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; font-size: 10pt;">Reg-ID</td>
                            <td><input type="text" name="loginform_regid" placeholder="1234" value="<?=$_POST["loginform_regid"]?>" style="width: 50px;" required/></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; font-size: 10pt;">Password (Opt.)</td>
                            <td><input type="password" name="loginform_password" placeholder="************" style="width: 200px;"/></td>
                        </tr>
                        <tr>
                            <td></td><td style="text-align: right;"><a style="font-size: 12px;" href="<?=domain?>">Back</a>&nbsp;&nbsp;<input type="submit" value="Login" style="border: 1px solid #006357; width: 60px;"/></td>
                        </tr>
                    </table>
                </form>
                Hint: A password is only required for admin-functionalities!
            </div>
        </div>
        <?php
        require_once(basetpl_footer);
    } else {
        die("Undefined state");
    }
?>