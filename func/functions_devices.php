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

class devices {

    /* getDeviceTemplates()
     *
     * This function returns name and ID of the available designtemplates
     *
     * @return ARRAY The available designtemplates as objects in an array
     */
    public function getDeviceTemplates() {
        //Gain db access
        global $db;

        //Query designtemplates from db
        $query = $db->query("SELECT `devicetemplateid`, `name` FROM `devicetemplates` ORDER BY `name` ASC LIMIT 200");
        if($db->isError()) { die($db->isError()); }

        //Create return-array
        $devicetemplates = array();
        while($row = mysqli_fetch_object($query)) {
            $devicetemplates[] = $row;
        }

        return $devicetemplates;
    }

    /* printDeviceTemplateList()
     *
     * This function prints a list of all available device-templates (editable)
     */
    public function printDeviceTemplateList() {
        //Gain db access
        global $db;

        //Query available templates
        $query = $db->query("SELECT * FROM `devicetemplates` ORDER BY `name` ASC LIMIT 200");
        if($db->isError()) { die($db->isError()); }

        //Check if there are templates in dataset
        if(mysqli_num_rows($query)<1) {
            ?><div>There are currently no devicetemplates listed in the database. <a href="#" onclick="spawnAddDeviceTemplateForm()">Create one!</a></div><?php
        }

        //Generate table from query
        ?>
        <div style="margin-bottom: 2px;">Below you find a table containing all device-templates.</div>
        <form style="display: inline;" method="POST" action="<?=domain?>index.php?p=devices" id="devicetpl_edit_form">
            <input type="hidden" name="devicetpl_edit_form_submitted" value="true"/>
            <input type="hidden" name="devicetpl_edit_form_action" id="devicetpl_edit_form_action" value="0"/>
            <input type="hidden" name="devicetpl_edit_devicetemplateid" id="devicetpl_edit_devicetemplateid" value=""/>
            <table class="gptable pocketpc_fill" style="margin-left: 0px;">
            <tr>
                <td class="gptable_head">DTID</td>
                <td class="gptable_head">Name</td>
                <td class="gptable_head">Description</td>
                <td class="gptable_head">QA</td>
                <td class="gptable_head"></td>
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
                    <td><?=$row->{"devicetemplateid"}?></td>
                    <td id="devicetpl_name_<?=$row->{"devicetemplateid"}?>"><?=$row->{"name"}?></td>
                    <td class="pocketPcBreakWord" id="devicetpl_description_<?=$row->{"devicetemplateid"}?>"><?=$row->{"description"}?></td>
                    <td id="devicetpl_allow_quickadd_<?=$row->{"devicetemplateid"}?>" data-allow-quickadd="<?=$row->{'allow_quickadd'}?>" style="text-align: center;">
                        <?php
                        if($row->{'allow_quickadd'} == 1) {
                            echo "<img src=\"".domain.dir_img."check.png\"/>";
                        } else {
                            echo "<img src=\"".domain.dir_img."cross.png\"/>";
                        }
                        ?>
                    </td>
                    <td class="pocketPcBreakWord" style="vertical-align: middle;" id="devicetpl_navi_<?=$row->{"devicetemplateid"}?>"><a href="#" onclick="editDeviceTemplate(<?=$row->{"devicetemplateid"}?>)" title="Edit"><img class="tableAction" src="<?=domain.dir_img?>edit.png"/></a>&nbsp;<a href="#" onclick="deleteDeviceTemplate(<?=$row->{"devicetemplateid"}?>)" title="Delete"><img class="tableAction" src="<?=domain.dir_img?>trashbin.png"/></a></td>
                </tr>
            <?php
            }
            ?></table>
            <i>DTID: DevicetemplateID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>QA: Quickadd</i>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="#" onclick="spawnAddDeviceTemplateForm()">New Devicetemplate</a><br/>
        </form>
        <?php

    }

    /* deviceTemplateEditFormSubmitted()
     *
     * This function checks if the deviceTemplateEditForm was submitted
     *
     * @return TRUE Form submitted
     * @return FALSE Form NOT submitted
     */
    public function deviceTemplateEditFormSubmitted() {
        if($_POST["devicetpl_edit_form_submitted"]) { return true; }
        return false;
    }

    /* proccessDeviceTemplateEdit()
     *
     * This function proccesses the submitted proccessDeviceTemplateEdit-Form
     *
     * @return STRING The message report
     */
    public function proccessDeviceTemplateEdit() {
        //Check if form was submitted
        if(!self::deviceTemplateEditFormSubmitted()) { return false; }

        //Gain db acess
        global $db;

        //Check desired action (1=Edit, 2=Delete)
        if($_POST["devicetpl_edit_form_action"] == 1) {
            //Proccess edit

            //Check if all required datafields are present
            if(!$_POST["devicetpl_edit_devicetemplateid"] || !$_POST["devicetpl_edit_name"]) { return "<b style=\"color: #EE0000;\">Error: Name can't be empty!!</b>"; }

            //Update database
            $allow_quickadd = ($_POST['devicetpl_edit_allow_quickadd'] == 'true') ? '1':'0';
            $db->query("UPDATE `devicetemplates` SET `name`='".$db->escape($_POST["devicetpl_edit_name"])."', `description`='".$db->escape($_POST["devicetpl_edit_description"])."', `allow_quickadd`='".$allow_quickadd."' WHERE `devicetemplateid`='".$db->escape($_POST["devicetpl_edit_devicetemplateid"])."' LIMIT 1");
            if($db->isError()) { die($db->isError()); }

            //Insert log
            global $core, $sessions;
            $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Updated devicetemplate with ID ".$_POST["devicetpl_edit_devicetemplateid"].". Set name to '".$_POST["devicetpl_edit_name"]."', description to '".$_POST["devicetpl_edit_description"]."' and allow_quickadd to '".$allow_quickadd."'.");

            return "";

        } elseif($_POST["devicetpl_edit_form_action"] == 2) {
            //Proccess delete
            if(!$_POST["devicetpl_edit_devicetemplateid"]) { return "<b style=\"color: #EE0000;\">Error: DTID not passed!</b>"; }

            //Get all devices that use the template from db and delete them
            $query_devices_with_template = $db->query("SELECT `deviceid` FROM `devices` WHERE `devicetemplateid`='".$db->escape($_POST["devicetpl_edit_devicetemplateid"])."'");
            if($db->isError()) { die($db->isError()); }
            while($row = mysqli_fetch_object($query_devices_with_template)) {
                self::deleteDevice($row->{"deviceid"});
            }

            //Delete template from db
            $db->query("DELETE FROM `devicetemplates` WHERE `devicetemplateid`='".$db->escape($_POST["devicetpl_edit_devicetemplateid"])."' LIMIT 1");
            if($db->isError()) { die($db->isError()); }

            //Insert log
            global $core, $sessions;
            $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Deleted devicetemplate with the ID ".$db->escape($_POST["devicetpl_edit_devicetemplateid"]).".");

            return "";
        }

        return false;

    }

    /* newDeviceTemplateFormSubmitted()
     *
     * This function checks if the newDeviceTemplateForm was submitted
     *
     * @return TRUE Form submitted
     * @return FALSE Form NOT submitted
     */
    public function newDeviceTemplateFormSubmitted() {
        if($_POST["newdevicetemplateform_submitted"]) { return true; }
        return false;
    }

    /* addNewDeviceTemplate()
     *
     * This function tries to add a new design template
     *
     * @param $new_name The new devicetemplate-name
     * @param $new_description The new devicetemplate-description
     * @param $allow_quickadd Sets the allow_quickadd flag if desired
     *
     * @return TRUE Success
     * @return FALSE Error
     */
    public function addNewDeviceTemplate($new_name, $new_description, $allow_quickadd) {
        //Check input
        if(!$new_name) { return false; }

        //Gain db access
        global $db;

        // Format quickadd
        if($allow_quickadd) {
            $allow_quickadd = 1;
        } else {
            $allow_quickadd = 0;
        }

        //Insert new template into device
        $db->query("INSERT INTO `devicetemplates` (`name`, `description`, `allow_quickadd`) VALUES ('".$db->escape($new_name)."', '".$db->escape($new_description)."', '".$allow_quickadd."')");
        if($db->isError()) { die($db->isError()); }

        //Insert log
        global $core, $sessions;
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Added new devicetemplate. Set name to '".$new_name."', description to '".$new_description."' and allow_quickadd to '".$allow_quickadd."'");

        return true;
    }

    /* generateRegisteredDevicesList()
     *
     * This function generates a list of all registered devices
     *
     * @param $search_field The field to perform a specific search in (If == false => no search is performed)
     * @param $search_value The value to search for
     * @param $availability (0=List all devices, 1=List only available devices, 2=List only bound devices)
     */
    public function generateRegisteredDevicesList($search_field, $search_value, $availability) {
        //Gain db access
        global $db;

        //Output search-form
        if(!$search_field) { $optionSelected = "callsign"; }
        else { $optionSelected = $search_field; }
        ?>
        <form method="POST" class="searchForm" id="deviceSearchForm" action="<?=domain?>index.php?p=devices" style="margin-bottom: 2px; magin-top: 5px;">
            <input type="hidden" name="serachRegisteredDevices_reset" id="serachRegisteredDevices_reset" value=""/>
            <span class="onlyPocketPC">Search:</span><br class="onlyPocketPc"/>
            <table>
                <tr>
                    <td class="removeOnPocketPc" style="text-align: right; font-size: 12px;">Search:</td>
                    <td style="text-align: left;">
                        <select name="searchRegisteredDevices_field" required>
                            <option value="deviceid" <?php if($optionSelected=="deviceid") echo "selected"; ?>>DeviceID</option>
                            <option value="name" <?php if($optionSelected=="name") echo "selected"; ?>>Name</option>
                            <option value="callsign" <?php if($optionSelected=="callsign") echo "selected"; ?>>Callsign</option>
                            <option value="serialnumber" <?php if($optionSelected=="serialnumber") echo "selected"; ?>>Serialnumber</option>
                            <option value="notes" <?php if($optionSelected=="notes") echo "selected"; ?>>Notes</option>
                        </select>
                        <input type="text" class="searchDevices" name="searchRegisteredDevices_value" value="<?=$search_value?>" placeholder="String to search for" />
                        <input class="removeOnPocketPc" type="submit" value="Submit"/>
                    </td>
                </tr>
                <tr>
                    <td class="removeOnPocketPc" style="text-align: right; font-size: 12px;">Display:</td>
                    <td style="text-align: left;">
                        <span class="onlyPocketPC">Display:</span>
                        <select name="searchRegisteredDevices_availability">
                            <option value="0" <?php if($availability=="0") echo "selected"; ?>>All registered devices</option>
                            <option value="1" <?php if($availability=="1") echo "selected"; ?>>Only available devices</option>
                            <option value="2" <?php if($availability=="2") echo "selected"; ?>>Only bound devices</option>
                        </select>
                        <?php if(pocketPc) { ?><input type="submit" value="Submit"/><?php } ?>
                    </td>
                </tr>
            </table>
        </form>
        <?php

        //Check if on PocketPC and if search was submitted
        if(!(pocketPc && !$search_field && !$search_value)) {
            //Build search-argument if necessary
            if(!$search_value) { $search_field=false; }
            switch($search_field) {
                case "deviceid": $sqlSearch = "`deviceid` LIKE '%".$db->escape($search_value)."%'"; break;
                case "name": $sqlSearch = "`name` LIKE '%".$db->escape($search_value)."%'"; break;
                case "callsign": $sqlSearch = "`callsign` LIKE '%".$db->escape($search_value)."%'"; break;
                case "serialnumber": $sqlSearch = "`serialnumber` LIKE '%".$db->escape($search_value)."%'"; break;
                case "notes": $sqlSearch = "`notes` LIKE '%".$db->escape($search_value)."%'"; break;
                default: $sqlSearch = "1=1"; break;
            }

            //Query all registered devices
            $query_devices = $db->query("
                SELECT  devices.*, devicetemplates.`name`
                FROM `devices` devices, `devicetemplates` devicetemplates
                WHERE devices.`devicetemplateid` = devicetemplates.`devicetemplateid` AND ".$sqlSearch."
                ORDER BY `name` ASC, `callsign` ASC, `deviceid` ASC");
            if($db->isError()) { die($db->isError()); }

            //Querry database for bindings
            $query_bindings = $db->query("SELECT `deviceid` FROM `bindings`");
            if($db->isError()) { die($db->isError()); }

            //Generate array of bound devices
            $boundDevices = array();
            while($row = mysqli_fetch_object($query_bindings)) { $boundDevices[] = $row->{"deviceid"}; }

            //Generate output data array
            $outputDevices = array();
            switch($availability) {
                //Only available devices
                case 1:
                    while($row = mysqli_fetch_object($query_devices)) {
                        if(!in_array($row->{"deviceid"}, $boundDevices)) {
                            $outputDevices[] = $row;
                        }
                    }
                    break;
                //Only bound devices
                case 2:
                    while($row = mysqli_fetch_object($query_devices)) {
                        if(in_array($row->{"deviceid"}, $boundDevices)) {
                            $outputDevices[] = $row;
                        }
                    }
                    break;
                //All devices
                case 0:
                default: while($row = mysqli_fetch_object($query_devices)) { $outputDevices[] = $row; } break;
            }

            //Check if devices are present
            if(sizeof($outputDevices)<1) {
                ?><div>There are currently no matching devices registered in the database. <a href="#" onclick="spawnNewDeviceSingleForm()">New Device (Single)</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="#" onclick="spawnNewDeviceMultiForm()">New Devices (Multi)</a></div><?php
                return true;
            }

            //Generate output table head
            ?>
            <div class="devicelist_wrapper" id="devicelist_wrapper">
            <table class="devicelist pocketpc_fill" id="devicelist_table" style="margin-left: 0px;">
                <tr>
                    <td class="devicelist_head">DID</td>
                    <td class="devicelist_head">Av</td>
                    <td class="devicelist_head">Name (DTID)</td>
                    <td class="devicelist_head">CS</td>
                    <td class="devicelist_head removeOnPocketPc">S/N</td>
                    <td class="devicelist_head removeOnPocketPc">Notes</td>
                    <td class="devicelist_head">&nbsp;</td>
                </tr>
            <?php

            //Spit out devices
            $row_color = "even";
            foreach($outputDevices as $device) {
                if($row_color == "even") { $row_color = "odd"; }
                else { $row_color = "even"; }
                ?>
                <tr class="devicelist_<?=$row_color?>">
                    <td><?=$device->{"deviceid"}?></td>
                    <td style="text-align: center;"><?php if(in_array($device->{"deviceid"}, $boundDevices)) { echo "<img src=\"".domain.dir_img."cross.png\"/>"; } else { echo "<img src=\"".domain.dir_img."check.png\"/>"; } ?></td>
                    <td class="pocketPcBreakWord"><?=$device->{"name"}?>&nbsp;(<?=$device->{"devicetemplateid"}?>)</td>
                    <td><?=$device->{"callsign"}?></td>
                    <td class="removeOnPocketPc"><?=$device->{"serialnumber"}?></td>
                    <td class="removeOnPocketPc"><?=$device->{"notes"}?></td>
                    <td style="vertical-align: middle;"><a href="#" onclick="deleteDevice('<?=$device->{"deviceid"}?>')" title="Delete device!"><img class="tableAction" src="<?=domain.dir_img?>trashbin.png"/></a></td>
                </tr>
            <?php
            }

            ?></table></div><i>Av: Available</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>DID: DeviceID</i>&nbsp;&nbsp;-&nbsp;&nbsp;<i>DTID: DevicetemplateID</i><br/>
        <?php } ?>
        <a href="#" onclick="spawnNewDeviceSingleForm()">New Device (Single)</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="#" onclick="spawnNewDeviceMultiForm()">New Devices (Multi)</a><br/><?php

        return true;
    }

    /* newDeviceSingleFormSubmitted()
     *
     * Checks if newDeviceSingleForm was submitted
     *
     * @return TRUE Form was submitted
     * @return FALSE Form was NOT submitted
     */
    public function newDeviceSingleFormSubmitted() {
        if($_POST["newDeviceSingleForm_submitted"]) { return true; }
        return false;
    }

    /* newDeviceMultiFormSubmitted()
     *
     * Checks if newDeviceMultiFormSubmitted was submitted
     *
     * @return TRUE Form was submitted
     * @return FALSE Form was NOT submitted
     */
    public function newDeviceMultiFormSubmitted() {
        if($_POST["newDeviceMultiForm_submitted"]) { return true; }
        return false;
    }


    /* createSingleDevice()
     *
     * This function creates one single device
     *
     * @param $devicetemplateid The desired template
     * @param $callsign (Opt) The callsign
     * @param $serialnumber (Opt) The serialnumber
     * @param $notes (Opt) Notes
     *
     * @return TRUE Device created
     * @return FALSE Error
     */
    public function createSingleDevice($devicetemplateid, $callsign, $serialnumber, $notes) {
        //Check input
        if(!$devicetemplateid) { return false; }

        //Gain db access
        global $db;

        //Check if devicetemplate exist
        $query = $db->query("SELECT `devicetemplateid` FROM `devicetemplates` WHERE `devicetemplateid`='".$db->escape($devicetemplateid)."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }
        if(mysqli_num_rows($query)<1) { return false; }

        //Insert new device into database
        $db->query("INSERT INTO `devices` (`devicetemplateid`, `callsign`, `serialnumber`, `notes`) VALUES ('".$db->escape($devicetemplateid)."', '".$db->escape($callsign)."', '".$db->escape($serialnumber)."', '".$db->escape($notes)."')");
        if($db->isError()) { die($db->isError()); }

        //Insert log
        global $core, $sessions;
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Created new device. Set devicetemplateid to '".$devicetemplateid."', callsign to '".$callsign."', serialnumber to '".$serialnumber."' and notes to '".$notes."'.");

        return true;
    }

    /* createMultiDevices()
     *
     * This function creates a batch of devices
     *
     * @param $devicetemplateid The ID of the desired devicetemplate
     * @param $amount Amount of devices to create
     *
     * @return TRUE Devices created
     * @return FALSE Error
     */
    public function createMultiDevices($devicetemplateid, $amount) {
        //Check input
        if(!$devicetemplateid || !$amount || $amount < 1 || $amount > 100) { return false; }

        //Gain db access
        global $db;

        //Check if devicetemplate exist
        $query = $db->query("SELECT `devicetemplateid` FROM `devicetemplates` WHERE `devicetemplateid`='".$db->escape($devicetemplateid)."' LIMIT 1");
        if($db->isError()) { die($db->isError()); }
        if(mysqli_num_rows($query)<1) { return false; }

        //Insert some devices ;)
        $sqlValues = "";
        for($curDevice=0; $curDevice<$amount; $curDevice++) { $sqlValues.="(".$db->escape($devicetemplateid)."),"; }
        $sqlValues = rtrim($sqlValues, ",");
        $db->query("INSERT INTO `devices` (`devicetemplateid`) VALUES ".$sqlValues);
        if($db->isError()) { die($db->isError()); }

        //Insert log
        global $core, $sessions;
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Created ".$amount." new devices with devicetemplateid ".$devicetemplateid.".");

        return true;
    }

    /* deleteDeviceFormSubmitted()
     *
     * Checks if the deleteDevice-Form was submitted
     *
     * @return TRUE Form was submitted
     * @return FALSE Form was NOT submitted
     */
    public function deleteDeviceFormSubmitted() {
        if($_POST["deleteDeviceForm_submitted"]) { return true; }
        return false;
    }

    /* deleteDevice()
     *
     * This function deletes a device from `devices` and all corresponding
     * bindings from `bindings`
     *
     * @param $deviceid The ID of the device that will be deleted
     *
     * @return TRUE Device deleted
     * @return FALSE Error
     */
    public function deleteDevice($deviceid) {
        //Check input
        if($deviceid<1) { return false; }

        //Gain db access
        global $db;

        //Remove bindings
        $db->query("DELETE FROM `bindings` WHERE `deviceid`='".$db->escape($deviceid)."'");
        if($db->isError()) { die($db->isError()); }

        //Add log
        global $core, $sessions;
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Deleted all bindings associated with the device with DID ".$deviceid.".");

        //Remove device
        $db->query("DELETE FROM `devices` WHERE `deviceid`='".$db->escape($deviceid)."'");
        if($db->isError()) { die($db->isError()); }

        //Add log
        $core->addLog($sessions->getUserName()." (UID: ".$sessions->getUserId().")", "Deleted device with DID ".$deviceid.".");

        return true;
    }

    /* searchDevices()
     *
     * This function searches for devices and returns
     * them in an array
     *
     * @param $searchValue The desired search-string
     * @param $deviceIdOverride Absolute Device-ID
     */
    public function searchDevices($searchValue, $deviceIdOverride, $includeUnboundDevices = false) {
        //Check input
        if(!$searchValue && !$deviceIdOverride) { return false; }

        //Gain db access
        global $db;

        //Query all registered devices
        if($deviceIdOverride) {
            $query_devices = $db->query("
            SELECT  devices.*, devicetemplates.`name`
            FROM `devices` devices, `devicetemplates` devicetemplates
            WHERE devices.`devicetemplateid` = devicetemplates.`devicetemplateid`
            AND `deviceid`='".$db->escape($deviceIdOverride)."'
            ORDER BY `name` ASC, `callsign` ASC, `deviceid` ASC");
        } else {
            $query_devices = $db->query("
            SELECT  devices.*, devicetemplates.`name`
            FROM `devices` devices, `devicetemplates` devicetemplates
            WHERE devices.`devicetemplateid` = devicetemplates.`devicetemplateid`
            AND (`serialnumber`='".$db->escape($searchValue)."' OR `callsign`='".$db->escape($searchValue)."' OR `deviceid`='".$db->escape($searchValue)."' OR `name` LIKE '%".$db->escape($searchValue)."%')
            ORDER BY `name` ASC, `callsign` ASC, `deviceid` ASC");
        }
        if($db->isError()) { die($db->isError()); }

        //Querry database for bindings
        $query_bindings = $db->query("SELECT `deviceid` FROM `bindings`");
        if($db->isError()) { die($db->isError()); }

        //Generate array of bound devices
        $boundDevices = array();
        while($row = mysqli_fetch_object($query_bindings)) { $boundDevices[] = $row->{"deviceid"}; }

        //Generate output data array of only available devices
        $outputDevices = array();
        while($row = mysqli_fetch_object($query_devices)) {
            if($includeUnboundDevices || !in_array($row->{"deviceid"}, $boundDevices)) {
                $outputDevices[$row->{"deviceid"}] = $row;
            }
        }

        return $outputDevices;
    }

    /**
     * Gets all devicetemplates that allow quickadd from db and check the availability for each
     *
     * @return array|null
     */
    public function getQuickaddDevices() {
        global $db;
        $quickadd_data = array();

        // Query DB
        $query_quickadd = $db->query("
            SELECT devicetemplates.`devicetemplateid`, devicetemplates.`name`, SUM(if(devices.`deviceid` NOT IN (SELECT `deviceid` FROM `bindings` UNION ALL SELECT -1),1,0)) AS available
            FROM devicetemplates
            LEFT JOIN devices
            ON (devicetemplates.`devicetemplateid` = devices.`devicetemplateid`)
            WHERE devicetemplates.`allow_quickadd` = 1
            GROUP BY devicetemplates.`devicetemplateid`
        ");
        if($db->isError()) { die($db->isError()); }

        // Build response from query
        while($row = mysqli_fetch_array($query_quickadd)) {
            $quickadd_data[$row['devicetemplateid']] = $row;
        }

        // Subtract devices that are already about to be bound
        foreach($_SESSION["addBinding"]["devices"] as $device) {
            if(isset($quickadd_data[$device->{'devicetemplateid'}])) {
                $quickadd_data[$device->{'devicetemplateid'}]['available']--;
            }
        }

        return $quickadd_data;
    }

}