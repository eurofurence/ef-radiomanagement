<?php
/**
 * Created by Niels Gandraß.
 * Copyright (C) 2015
 */

class bindings {

    /* generateCallsignList()
     *
     * This function generates a list of all currently bound callsigns
     */
    public function generateCallsignList() {
        //Gain db access
        global $db;

        //Query all assigned callsigns (Attention crazy sql-query incoming)
        $query=$db->query("
            SELECT SQL_NO_CACHE devices.`callsign`, users.`nickname`, users.`regid`
            FROM `bindings` bindings, `devices` devices, `users` users
            WHERE
            bindings.`userid` = users.`userid` AND
            bindings.`deviceid` = devices.`deviceid` AND
            devices.`callsign` IS NOT NULL AND
            NOT devices.`callsign` = ''
            ORDER BY `nickname`, `callsign` ASC");
        if($db->isError()) { die($db->isError()); }

        //Check if results are present
        if(mysqli_num_rows($query)<1) {
            ?><div>There are currently no callsigns assigned!<br/><br/><a href="<?=domain?>"><b>Back</b></a></div><?php
            return true;
        }

        //Generate output table
        ?>
        If you find an invalid callsing please contact the radio-team!<br/>
        <br/>
        <table class="gptable pocketpc_fill">
            <tr>
                <td class="gptable_head">Nickname</td>
                <td class="gptable_head">CS</td>
            </tr>
        <?php
        //Generate data rows
        $row_color = "even";
        while($row = mysqli_fetch_object($query)) {
            //Adjust row-color
            if($row_color == "even") { $row_color = "odd"; }
            else { $row_color = "even"; }
            ?>
            <tr class="gptable_<?=$row_color?>">
                <td><?=$row->{"nickname"}?></td>
                <td><?=$row->{"callsign"}?></td>
            </tr>
            <?php
        }
        ?></table><?php

        return true;
    }

    /* printUserBindungs()
     *
     * This function prints the users bindings
     *
     * @param $userid The users ID
     */
    public function printUserBindings($userid) {
        //Gain db access
        global $db;

        //Query database for existing bindings
        $query = $db->query("
            SELECT bindings.`bindingid` ,bindings.`deviceid`, devicetemplates.`name`, bindings.`bound_since`
            FROM `bindings` bindings, `devices` devices, `devicetemplates` devicetemplates
            WHERE
            bindings.`userid` = '".$db->escape($userid)."' AND
            bindings.`deviceid` = devices.`deviceid` AND
            devices.`devicetemplateid` = devicetemplates.`devicetemplateid`
            ORDER BY `name` ASC;");
        if($db->isError()) { die($db->isError()); }

        //Check if devices were assigned
        if(mysqli_num_rows($query)<1) {
            ?><div>You currently have no assigned devices!</div><?php
            return true;
        }

        //Generate output table
        ?>
        You will find all radios and accessories assigned to you in the table below.<br/>
        <br/>
        <table class="gptable pocketpc_fill">
            <tr>
                <td class="gptable_head pocketPcBreakWord">Item</td>
                <td class="gptable_head">Assigned</td>
                <td class="gptable_head">BID</td>
                <td class="gptable_head">DID</td>
            </tr>
            <?php
            //Generate data rows
            $row_color = "even";
            while($row = mysqli_fetch_object($query)) {
                //Adjust row-color
                if($row_color == "even") { $row_color = "odd"; }
                else { $row_color = "even"; }
                ?>
                <tr class="gptable_<?=$row_color?>">
                    <td><?=$row->{"name"}?></td>
                    <td><?=date("d.m.y H:i:s", strtotime($row->{"bound_since"}))?></td>
                    <td><?=$row->{"bindingid"}?></td>
                    <td><?=$row->{"deviceid"}?></td>
                </tr>
            <?php
            }
            ?></table><i>BID: Binding-ID&nbsp;-&nbsp;DID: Device-ID</i><?php

        return true;
    }
    /* generateBindingsList()
     *
     * This function generates a list of all active bindings
     *
     * @param $search_field The field to perform a specific search in (If == false => no search is performed)
     * @param $search_value The value to search for
     */
    public function generateBindingsList($search_field, $search_value) {
        //Gain db access
        global $db;

        //Reset serach_field if search_value is not present
        if(!$search_value) { $search_field = false; }

        //Generate serach form
        ?>
        <form method="POST" class="searchForm" id="bindingListSearchForm" action="<?=domain?>index.php?p=bindings" style="margin-bottom: 2px; magin-top: 5px;">
            <table>
                <tr>
                    <td class="removeOnPocketPc" style="text-align: right; font-size: 12px;">Search:</td>
                    <td style="text-align: left;">
                        <span class="onlyPocketPC">Search:</span><br class="onlyPocketPc">
                        <select name="bindingListSearchForm_field" id="sfield" required>
                            <option value="nickname" <?php if($search_field=="nickname") echo "selected"; ?>>Nickname</option>
                            <option value="deviceid" <?php if($search_field=="deviceid") echo "selected"; ?>>Device-ID</option>
                            <option value="callsign" <?php if($search_field=="callsign") echo "selected"; ?>>Callsign</option>
                            <option value="userid" <?php if($search_field=="userid") echo "selected"; ?>>User-ID</option>
                        </select>
                        <input type="text" id="svalue" name="bindingListSearchForm_value" value="<?=$search_value?>" placeholder="String to search for" class="searchBindings"/>
                        <input class="searchBindings_submit" type="submit" value="Submit"/>
                    </td>
                </tr>
            </table>
        </form>
        <?php

        //Remove default list 4 pocket PC (Only visible after search)
        if(!(pocketPc && !$search_field && !$search_value)) {
            //Get bindings from database
            $query_bindings = $db->query("SELECT * FROM `bindings`");
            if($db->isError()) { die($db->isError()); }
            if(mysqli_num_rows($query_bindings) < 1) {
                ?><b>There are currently no bindings!</b><?php
                return false;
            }

            //Get user-details and generate user-array
            $query_users = $db->query("SELECT `userid`, `nickname` FROM `users`");
            if($db->isError()) { die($db->isError()); }
            $users = array();
            while($row = mysqli_fetch_object($query_users)) {
                $users[$row->{"userid"}] = $row->{"nickname"};
            }

            //Get devices and generate device-array
            $query_devices = $db->query("
                SELECT devices.`deviceid`, devices.`callsign`, devicetemplates.`name`
                FROM `devicetemplates` devicetemplates, `devices` devices
                WHERE devices.`devicetemplateid`=devicetemplates.`devicetemplateid`
                ORDER BY devices.`deviceid` ASC");
            if($db->isError()) { die($db->isError()); }
            $devices = array();
            while($row = mysqli_fetch_object($query_devices)) {
                $devices[$row->{"deviceid"}] = $row;
            }


            //Generate output table
            ?>
            <div class="bindinglist_wrapper" id="bindinglist_wrapper">
            <table class="gptable">
                <tr>
                    <td class="gptable_head">BID</td>
                    <td class="gptable_head">User (UID)</td>
                    <td class="gptable_head">Device (DID)</td>
                    <td class="gptable_head">CS</td>
                    <td class="gptable_head">Bound Since</td>
                    <td class="gptable_head">Bound By</td>
                    <td class="gptable_head"></td>
                </tr>
            <?php
            $row_color = "even";
            while($row = mysqli_fetch_object($query_bindings)) {
                //Calculate row-color
                if($row_color == "even") { $row_color = "odd"; }
                else { $row_color = "even"; }

                //Check if search-parsing is required
                $outputRow = true;
                switch($search_field) {
                    case "nickname": if(strpos(strtolower($users[$row->{"userid"}]), strtolower($search_value)) === false) { $outputRow = false; } break;
                    case "deviceid": if(strpos($row->{"deviceid"}, $search_value) === false) { $outputRow = false; } break;
                    case "callsign": if(strpos($devices[$row->{"deviceid"}]->{"callsign"}, $search_value) === false) { $outputRow = false; } break;
                    case "userid": if(strpos($row->{"userid"}, $search_value) === false) { $outputRow = false; } break;
                }

                if($outputRow) {
                    ?>
                    <tr class="gptable_<?=$row_color?>">
                        <td><?=$row->{"bindingid"}?></td>
                        <td><?=$users[$row->{"userid"}]?> (<?=$row->{"userid"}?>)</td>
                        <td><?=$devices[$row->{"deviceid"}]->{"name"}?> (<?=$row->{"deviceid"}?>)</td>
                        <td><?=$devices[$row->{"deviceid"}]->{"callsign"}?></td>
                        <td><?=date("d.m.y H:i", strtotime($row->{"bound_since"}))?></td>
                        <td><?=$users[$row->{"bound_by"}]?> (<?=$row->{"bound_by"}?>)</td>
                        <td style="vertical-align: middle;"><a href="#" onclick="deleteBinding('<?=$row->{"bindingid"}?>')"><img class="tableAction" src="<?=domain.dir_img?>trashbin.png"</a></td>
                    </tr>
                    <?php
                }
            }
        }
        ?></table></div><?php
    }

    /* deleteBindingFormSubmitted()
     *
     * This function checks if the deleteBindingForm was submitted
     *
     * @return TRUE Form was submitted
     * @return FALSE Form was NOT submitted
     */
    public function deleteBindingFormSubmitted() {
        if($_POST["deleteBindingForm_submitted"]=="true") { return true; }
        return false;
    }

    /* deleteBinding()
     *
     * This function deletes a binding from the database
     *
     * @param $bindingid The desired binding's ID
     *
     * @return TRUE Success
     * @return FALSE Error
     */
    public function deleteBinding($bindingid) {
        //Check input
        if(!$bindingid || !is_numeric($bindingid)) { return false; }

        //Gain db access
        global $db;

        //Delete binding from database
        $db->query("DELETE FROM `bindings` WHERE `bindingid`='".$db->escape($bindingid)."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }

        //Insert Log
        global $core, $sessions;
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Binding with BID ".$bindingid." was deleted.");

        return true;
    }

    /* addBinding_printSearchUserForm()
     *
     * This function generates the form for the first step
     * of creating a new binding (Seraching for user)
     *
     * @param $userNotFound TRUE if no user was found
     */
    public function addBinding_printSearchUserForm($userNotFound) {
        ?>
        <span class="content_block">
        <div class="page_subtitle">Search for User</div>
        <hr class="header_spacer"/>
        <span class="content_block">Please scan or enter the desired users Registration-ID or Nickname!</span><br/>
            <table>
                <tr>
                    <td style="vertical-align: middle;"><img class="addBinding" src="<?=domain.dir_img?>usercard.png"/></td>
                    <td>&nbsp;&nbsp;</td>
                    <td style="vertical-align: middle;">
                        <form method="POST" action="<?=domain?>index.php?p=add_binding" name="addBinding_searchUserForm">
                            <span style="font-size: 12px;">Reg-ID / Nickname</span><br/>
                            <?php if($userNotFound) { ?><span class="addBindingError"><b style="color: #EE0000;">Error, no user was found!</b></span><br/><?php } ?>
                            <input type="hidden" name="addBinding_searchUserForm_submitted" value="true" />
                            <input type="text" name="addBinding_searchUserForm_searchString" class="addBinding" value="" placeholder="Enter or scan ID or nick!" size="30" autocomplete="off" required autofocus/><br/>
                            <input style="float: right; margin-top: 3px;" type="submit" value="Serach"/>
                        </form>
                    </td>
                </tr>
            </table>
        </span><br/>
        <?php

        return true;
    }

    /* searchUserForm_submitted()
     *
     * This function checks if the form was submitted
     *
     * @return TRUE Form was submitted
     * @return FALSE Form was NOT submitted
     */
     public function searchUserForm_submitted() {
         if($_POST["addBinding_searchUserForm_submitted"]) { return true; }

         return false;
     }

    /* addBinding_selectUser()
     *
     * This function checks if desired user was found and offers a selection
     *
     * @param $searchString The given search-value from addBinding_printSearchUserForm()
     * @param $userIdOverride If containing a user ID the given ID is selected
     */
    public function addBinding_selectUser($searchString, $userIdOverride) {
        //Search for users
        global $sessions;
        if(!$userIdOverride) {
            $searchUsers = $sessions->findUser($searchString, $searchString, false);
        } else {
            $searchUsers = $sessions->findUser(false, false, $userIdOverride);
        }

        //Check if users were found
        if(sizeof($searchUsers)<1) { self::addBinding_printSearchUserForm(true); return false; }

        //Check if multiple users were found
        if(sizeof($searchUsers)>1) {
            //Print gptable for user-selection
            ?>
            <span class="content_block">
            <div class="page_subtitle">Search for User</div>
            <hr class="header_spacer"/>
            <div style="margin-bottom: 2px;">Multiple users are matching your search. Please select one from below!</div>
            <div class="userlist_wrapper" id="userlist_wrapper">
            <table class="gptable pocketpc_fill" style="margin-left: 0px;">
                <tr>
                    <td class="gptable_head">UID</td>
                    <td class="gptable_head">RID</td>
                    <td class="gptable_head">Nickname</td>
                    <td class="gptable_head" style="width: 14px;">&nbsp;</td>
                </tr>
                <?php
                //Spit out devices
                $row_color = "even";
                foreach($searchUsers as $user) {
                    if($row_color == "even") { $row_color = "odd"; }
                    else { $row_color = "even"; }
                    ?>
                    <tr class="gptable_<?=$row_color?>">
                        <td><?=$user->{"userid"}?></td>
                        <td><?=$user->{"regid"}?></td>
                        <td><?=$user->{"nickname"}?></td>
                        <td style="vertical-align: middle; text-align: center;"><a href="<?=domain?>index.php?p=add_binding&searchUser_userid=<?=$user->{"userid"}?>" title="Select User"><img class="tableAction" src="<?=domain.dir_img?>check.png"/></a></td>
                    </tr>
                <?php
                }
            ?></table></div><i>UID: Userid</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>RID: RegID</i><br/>
            <?php
            return true;
        }

        //Only one user was found, print device-selection form
        $_SESSION["addBinding"]["user"] = array_values($searchUsers)[0];
        self::addBinding_printSearchDeviceForm(false);

        return true;
    }

    /* addBinding_printSearchDeviceForm()
     *
     * This function prints the searchDeviceForm
     *
     * @param $deviceNotFound TRUE if no device was found
     */
    public function addBinding_printSearchDeviceForm($deviceNotFound) {
        ?>
        <span class="content_block">
            <div class="page_subtitle">Search for Device</div>
            <hr class="header_spacer"/>
            <span class="content_block">Please scan or enter the desired device's callsign, name or DID!</span><br/>
            <br/>
            <table>
                <tr>
                    <td style="vertical-align: middle;"><img class="addBinding" src="<?=domain.dir_img?>barcode_scan.png"/></td>
                    <td>&nbsp;&nbsp;</td>
                    <td style="vertical-align: middle;">
                        <form method="POST" action="<?=domain?>index.php?p=add_binding" name="addBinding_searchDeviceForm">
                            <span style="font-size: 12px;">Callsign, Name or DID</span><br/>
                            <?php if($deviceNotFound) { ?><span class="addBindingError"><b style="color: #EE0000;">Error, no device was found!</b></span><br/><?php } ?>
                            <input type="hidden" name="addBinding_searchDeviceForm_submitted" value="true" />
                            <input type="text" name="addBinding_searchDeviceForm_searchString" class="addBinding" value="" placeholder="Enter or scan search value!" size="30" autocomplete="off" required autofocus/><br/>
                            <input style="float: right; margin-top: 3px;" type="submit" value="Serach"/>
                        </form>
                    </td>
                </tr>
            </table>
        </span><br/><br class="removeOnPocketPc"/>
        <b>Selected user:</b>&nbsp;<br class="onlyPocketPc"/><?=$_SESSION["addBinding"]["user"]->{"nickname"}." (RID: ".$_SESSION["addBinding"]["user"]->{"regid"}." - UID: ".$_SESSION["addBinding"]["user"]->{"userid"}.")"?> - <a href="<?domain?>index.php?p=add_binding">Cancel</a>
        <?php

        return true;
    }

    /* searchDeviceForm_submitted()
     *
     * Checks if searchDeviceForm was submitted
     *
     * @return TRUE Form was submitted
     * @return FALSE Form was NOT submitted
     */
    public function searchDeviceForm_submitted() {
        if($_POST["addBinding_searchDeviceForm_submitted"]) { return true; }
        return false;
    }

    /* addBinding_selectDevice()
     *
     * This function selects a device to bind, based on the search or the DID-Override
     *
     * @param $searchString Search-String from searchDeviceForm
     * @param $deviceIdOverride Absolute Device-ID
     */
    public function addBinding_selectDevice($searchString, $deviceIdOverride) {
        //Gain devices access
        $devices = new Devices();

        //Search for devices
        $searchedDevices = $devices->searchDevices($searchString, $deviceIdOverride);

        //Remove devices that are allready in $_SESSION["addBinding"]["devices"]
        if(isset($_SESSION["addBinding"]["devices"])) {
            foreach($_SESSION["addBinding"]["devices"] as $device) {
                if($searchedDevices[$device->{"deviceid"}]) {
                    unset($searchedDevices[$device->{"deviceid"}]);
                }
            }
        }

        //Check if devices were found
        if(sizeof($searchedDevices)<1) { self::addBinding_printSearchDeviceForm(true); return false; }

        //Check if multiple devices were found
        if(sizeof($searchedDevices)>1) {
            //Print selection table
            ?>
            <span class="content_block">
            <div class="page_subtitle">Search for Device</div>
            <hr class="header_spacer"/>
            <div style="margin-bottom: 2px;">Multiple devices are matching your search. Please select one from below!</div>
            <div class="devicelist_wrapper" id="devicelist_wrapper">
                <table class="devicelist" id="devicelist_table" style="margin-left: 0px;">
                    <tr>
                        <td class="devicelist_head">DID</td>
                        <td class="devicelist_head">Name (DTID)</td>
                        <td class="devicelist_head">CS</td>
                        <td class="devicelist_head removeOnPocketPc">S/N</td>
                        <td class="devicelist_head removeOnPocketPc">Notes</td>
                        <td class="devicelist_head" style="width: 14px;">&nbsp;</td>
                    </tr>
                    <?php

                    //Spit out devices
                    $row_color = "even";
                    foreach($searchedDevices as $device) {
                        if($row_color == "even") { $row_color = "odd"; }
                        else { $row_color = "even"; }
                        ?>
                        <tr class="devicelist_<?=$row_color?>">
                            <td><?=$device->{"deviceid"}?></td>
                            <td class="pocketPcBreakWord"><?=$device->{"name"}?>&nbsp;(<?=$device->{"devicetemplateid"}?>)</td>
                            <td><?=$device->{"callsign"}?></td>
                            <td class="removeOnPocketPc"><?=$device->{"serialnumber"}?></td>
                            <td class="removeOnPocketPc"><?=$device->{"notes"}?></td>
                            <td style="vertical-align: middle; text-align: center;"><a href="<?=domain?>index.php?p=add_binding&searchDevice_deviceid=<?=$device->{"deviceid"}?>" title="Select device!"><img class="tableAction" src="<?=domain.dir_img?>check.png"/></a></td>
                        </tr>
                    <?php
                    }
                ?></table></div><i>DID: DeviceID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>DTID: DevicetemplateID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>CS: Callsign</i><br/><?php

            return false;
        }

        //Only one device found, add to SESSION and print review
        $_SESSION["addBinding"]["devices"][array_values($searchedDevices)[0]->{"deviceid"}]=array_values($searchedDevices)[0];
        self::addBinding_printReviewForm();

        return true;
    }

    /* addBinding_printReviewForm()
     *
     * This function prints the review-form for device adding!
     */
    public function addBinding_printReviewForm() {
        ?>
        <span class="content_block">
            <div class="page_subtitle">Review new binding</div>
            <hr class="header_spacer"/>
            <span style="margin-bottom: 2px;" class="content_block">The following bindings are about to be made to the user <b><?=$_SESSION["addBinding"]["user"]->{"nickname"}." (RID: ".$_SESSION["addBinding"]["user"]->{"regid"}." - UID: ".$_SESSION["addBinding"]["user"]->{"userid"}.")"?></b>!</span><br/>
            <div class="devicelist_wrapper" id="devicelist_wrapper">
                <table class="devicelist" id="devicelist_table" style="margin-left: 0px;">
                    <tr>
                        <td class="devicelist_head">DID</td>
                        <td class="devicelist_head">Name (DTID)</td>
                        <td class="devicelist_head">CS</td>
                        <td class="devicelist_head removeOnPocketPc">S/N</td>
                        <td class="devicelist_head removeOnPocketPc">Notes</td>
                        <td class="devicelist_head" style="width: 14px;">&nbsp;</td>
                    </tr>
                    <?php
                    //Spit out devices
                    $row_color = "even";
                    foreach($_SESSION["addBinding"]["devices"] as $device) {
                        if($row_color == "even") { $row_color = "odd"; }
                        else { $row_color = "even"; }
                        ?>
                        <tr class="devicelist_<?=$row_color?>">
                            <td><?=$device->{"deviceid"}?></td>
                            <td><?=$device->{"name"}?>&nbsp;(<?=$device->{"devicetemplateid"}?>)</td>
                            <td><?=$device->{"callsign"}?></td>
                            <td class="removeOnPocketPc"><?=$device->{"serialnumber"}?></td>
                            <td class="removeOnPocketPc"><?=$device->{"notes"}?></td>
                            <td style="vertical-align: middle; text-align: center;"><a href="<?=domain?>index.php?p=add_binding&addBinding_removeDevice=<?=$device->{"deviceid"}?>" title="Remove device!"><img class="tableAction" src="<?=domain.dir_img?>trashbin.png"/></a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <span style="margin-top: 2px; width: 100%; text-align: center; display: inline-block;"><a href="<?=domain?>index.php?p=add_binding&saveBinding=true" title="Create Binding"><img src="<?=domain.dir_img?>addBinding.png"/>&nbsp;<b>Create Binding!</b></a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="<?=domain?>index.php?p=add_binding&addBinding_additionalDevice=true" title="Add another device">Add another device!</a></span><br/>
        </span><br/>
        <?php
        return true;
    }

    /* addBinding_saveBindings()
     *
     * This function saves all in $_SESSION["addBinding"] defined bindings
     *
     * @return TRUE Success, all bindings saved
     * @return FALSE Error, devices aren't available anymore :(
     */
    public function addBinding_saveBindings() {
        //Check if devices are to be bound
        if(!isset($_SESSION["addBinding"]["user"]) || !isset($_SESSION["addBinding"]["devices"])) {
            return false;
        }

        //Gain db and sessions access
        global $db;
        global $sessions;

        //Check if user-data is valid
        if(!$_SESSION["addBinding"]["user"]->{"userid"}) { return false; }
        $query = $db->query("SELECT COUNT(*) FROM `users` WHERE `userid`='".$db->escape($_SESSION["addBinding"]["user"]->{"userid"})."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }
        if(mysqli_fetch_object($query)->{"COUNT(*)"}!=1) { return false; }

        //Check if devices are still available
        $deviceIdsSearchSql = "TRUE=FALSE";
        foreach($_SESSION["addBinding"]["devices"] as $device) {
            $deviceIdsSearchSql .= " OR devices.`deviceid`='".$db->escape($device->{"deviceid"})."'";
        }
        $query = $db->query("
            SELECT COUNT(*)
            FROM `devices` devices, `bindings` bindings
            WHERE devices.`deviceid`=bindings.`deviceid`
            AND (".$deviceIdsSearchSql.")");
        if($db->isError()) { die($db->isError()); }
        if(mysqli_fetch_object($query)->{"COUNT(*)"}>0) { return false; }

        //Insert new bindings
        $bindingValues = "";
        foreach($_SESSION["addBinding"]["devices"] as $device) {
            $bindingValues .= "('".$db->escape($_SESSION["addBinding"]["user"]->{"userid"})."',
                                '".$db->escape($device->{"deviceid"})."',
                                '".$db->escape(date("Y-m-d H:i:s"))."',
                                '".$db->escape($sessions->getUserId())."'),";
        }
        $bindingValues = trim($bindingValues, ",");
        $query = $db->query("INSERT INTO `bindings` (`userid`, `deviceid`, `bound_since`, `bound_by`) VALUES ".$bindingValues);
        if($db->isError()) { die($db->isError()); }

        //Insert Log
        global $core;
        foreach($_SESSION["addBinding"]["devices"] as $device) {
            $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")",
            "Bound device '".$device->{"name"}." (DID: ".$device->{"deviceid"}.")' to user '".$_SESSION["addBinding"]["user"]->{"nickname"}." (UID: ".$_SESSION["addBinding"]["user"]->{"userid"}." - RID: ".$_SESSION["addBinding"]["user"]->{"regid"}.")'.");
        }

        return true;
    }

    /* addBinding_saveBindingsCatch()
     *
     * This function interprets the result of addBinding_saveBindings()
     */
    public function addBinding_saveBindingsCatch() {
        //Tries to save current bindings
        if(self::addBinding_saveBindings()) {
            ?>
            <b style="color: #11922E">Success, desired bindings were established! :)</b><br/><br/>
            <a href="<?domain?>index.php?p=add_binding">Add another Binding</a>
            <?php
            unset($_SESSION["addBinding"]);
        } else {
            ?><b style="color: #EE0000;">Error, desired devices were allready bound! Resetting.</b><?php
        }
    }
}

?>