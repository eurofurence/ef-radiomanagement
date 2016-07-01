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

require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
require_once(func_devices);
$devices = new devices();
$bindings = new bindings();
?>

<td class="content_td">
    <div class="page_title">Add Binding</div>
    <hr class="header_spacer"/>
    <span style="display: inline-block; max-width: 600px;">
        You can add new bindings on this page. Please create the device as well as the desired user first!
    </span><br/>
    <br class="small"/>
    <?php
        //Check if GET-Override for user-selection is active
        if(!$_POST["addBinding_searchUserForm_searchString"] && $_GET["searchUser_userid"]) {
            $_POST["addBinding_searchUserForm_submitted"] = true;
        }

        //Check if GET-Override for device-selection is active
        if(!$_POST["addBinding_searchDeviceForm_searchString"] && $_GET["searchDevice_deviceid"]) {
            $_POST["addBinding_searchDeviceForm_submitted"] = true;
        }

        //Check for current form-state
        if($bindings->searchUserForm_submitted()) {
            $bindings->addBinding_selectUser($_POST["addBinding_searchUserForm_searchString"], $_GET["searchUser_userid"]);
        } elseif($bindings->searchDeviceForm_submitted()) {
            $bindings->addBinding_selectDevice($_POST["addBinding_searchDeviceForm_searchString"], $_GET["searchDevice_deviceid"]);
        } elseif($_GET["addBinding_additionalDevice"]=="true") {
            $bindings->addBinding_printSearchDeviceForm(false);
        } elseif(isset($_GET["addBinding_removeDevice"])) {
            //Remove desired device from $_SESSION and regenerate review-form
            unset($_SESSION["addBinding"]["devices"][$_GET["addBinding_removeDevice"]]);
            if(sizeof($_SESSION["addBinding"]["devices"])<1) { $bindings->addBinding_printSearchDeviceForm(false); }
            else { $bindings->addBinding_printReviewForm(); }
        } elseif($_GET["saveBinding"]=="true") {
            $bindings->addBinding_saveBindingsCatch();
        } else {
            unset($_SESSION["addBinding"]);
            $bindings->addBinding_printSearchUserForm(false);
        }
    ?>
</td>

<?php require_once(basetpl_footer); ?>