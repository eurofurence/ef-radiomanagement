<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2016 Niels Gandraß <ngandrass@squacu.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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
        <td class="public_title">
            <div class="title"><a href="<?=domain?>"><img class="titleBanner" src="<?=domain.dir_img?>title.jpg"/></a></div>
            <br/>
            <div class="login_content_container">
                <div class="page_title">System Login</div>
                <br/>
                <!-- Login for big screen devices -->
                <div class="login_data_box_big">
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
                                <td>&nbsp;</td>
                                <td><input type="text" name="loginform_nickname" placeholder="Randomfur" value="<?=$_POST["loginform_nickname"]?>" style="width: 200px;" required/></td>
                            </tr>
                            <tr>
                                <td style="text-align: right; font-size: 10pt;">Reg-ID</td>
                                <td>&nbsp;</td>
                                <td><input type="text" name="loginform_regid" placeholder="1234" value="<?=$_POST["loginform_regid"]?>" style="width: 50px;" required/></td>
                            </tr>
                            <tr>
                                <td style="text-align: right; font-size: 10pt;">Password (Opt.)</td>
                                <td>&nbsp;</td>
                                <td><input type="password" name="loginform_password" placeholder="************" style="width: 200px;"/></td>
                            </tr>
                            <tr>
                                <td></td><td>&nbsp;</td><td style="text-align: right;"><a style="font-size: 12px;" href="<?=domain?>">Back</a>&nbsp;&nbsp;<input type="submit" value="Login" style="border: 1px solid #006357; width: 60px;"/></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <!-- Login for small-screen devices -->
                <div class="login_data_box_small">
                    <?php
                    //Check for login errors
                    if($sessions->error_login_not_found) { ?><div class="login_error">Combination not found in database!</div><?php }
                    if($sessions->error_login_data_not_entered) { ?><div class="login_error">Please enter Nickname and Reg-ID!</div><?php }
                    ?>
                    <form method="POST" action="index.php?p=login">
                        <input type="hidden" name="loginform_submitted" value="true"/>
                            <span>Nickname</span><br/>
                            <input type="text" name="loginform_nickname" placeholder="Randomfur" value="<?=$_POST["loginform_nickname"]?>" style="width: 200px;" required/><br/>
                            <span>Reg-ID</span><br/>
                            <input type="text" name="loginform_regid" placeholder="1234" value="<?=$_POST["loginform_regid"]?>" style="width: 50px;" required/><br/>
                            <span>Password (Opt.)</span><br/>
                            <input type="password" name="loginform_password" placeholder="************" style="width: 200px;"/><br/>
                            <div style="width: 100%; text-align: right; margin-top: 4px;"><a style="font-size: 12px;" href="<?=domain?>">Back</a>&nbsp;&nbsp;<input type="submit" value="Login" style="border: 1px solid #006357; width: 60px;"/></div>
                    </form>
                </div>
                <br/><span>Hint: A password is only required for admin-functionalities!</span>
            </div>
        </td>
        <?php
        require_once(basetpl_footer);
    } else {
        die("Undefined state");
    }
?>