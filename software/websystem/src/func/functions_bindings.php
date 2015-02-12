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
            devices.`callsign` IS NOT NULL
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
        <table class="gptable">
            <tr>
                <td class="gptable_head">Nickname</td>
                <td class="gptable_head">Callsign</td>
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
        <table class="gptable">
            <tr>
                <td class="gptable_head">Item</td>
                <td class="gptable_head">Assigned since</td>
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
        //FIXME: Implment serach function
        //Gain db access
        global $db;

        //Reset serach_field if search_value is not present
        if(!$search_value) { $search_field = false; }

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

        //Generate serach form
        ?>
        <form method="POST" id="bindingListSearchForm" action="<?=domain?>index.php?p=bindings" style="margin-bottom: 2px; magin-top: 5px;">
            <table>
                <tr>
                    <td style="text-align: right; font-size: 12px;">Search:</td>
                    <td style="text-align: left;">
                        <select name="bindingListSearchForm_field" required>
                            <option value="nickname" <?php if($search_field=="nickname") echo "selected"; ?>>Nickname</option>
                            <option value="deviceid" <?php if($search_field=="deviceid") echo "selected"; ?>>Device-ID</option>
                            <option value="callsign" <?php if($search_field=="callsign") echo "selected"; ?>>Callsign</option>
                            <option value="userid" <?php if($search_field=="userid") echo "selected"; ?>>User-ID</option>
                        </select>
                        <input type="text" name="bindingListSearchForm_value" value="<?=$search_value?>" placeholder="String to search for" style="width: 200px;"/>
                        <input type="submit" value="Submit"/>
                    </td>
                </tr>
            </table>
        </form>
        <?php

        //Generate output table
        ?>
        <div class="bindinglist_wrapper" id="bindinglist_wrapper">
        <table class="gptable">
            <tr>
                <td class="gptable_head">BID</td>
                <td class="gptable_head">User</td>
                <td class="gptable_head">Device</td>
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
                case "nickname": if(strpos($users[$row->{"userid"}], $search_value) === false) { $outputRow = false; } break;
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
                    <td style="vertical-align: middle;"><a href="#" onclick="deleteBinding('<?=$row->{"bindingid"}?>')"><img src="<?=domain.dir_img?>trashbin.png"</a></td>
                </tr>
                <?php
            }
        }
        ?></table></div><?php
    }
}

?>