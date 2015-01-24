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
        <table class="callsignlist">
            <tr>
                <td class="callsignlist_head">Nickname</td>
                <td class="callsignlist_head">Callsign</td>
            </tr>
        <?php
        //Generate data rows
        $row_color = "even";
        while($row = mysqli_fetch_object($query)) {
            //Adjust row-color
            if($row_color == "even") { $row_color = "odd"; }
            else { $row_color = "even"; }
            ?>
            <tr class="callsignlist_<?=$row_color?>">
                <td><?=$row->{"nickname"}?></td>
                <td><?=$row->{"callsign"}?></td>
            </tr>
            <?php
        }
        ?></table><br/><a href="<?=domain?>">Back</a><?php

        return true;
    }
}

?>