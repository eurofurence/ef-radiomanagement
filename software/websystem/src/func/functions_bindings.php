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
}

?>