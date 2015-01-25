<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
require_once(func_devices);
$bindings = new bindings();
$devices = new devices();

//Check if deviceTemplateEditForm was submitted
$deviceTemplateFormEditMessage = $devices->proccessDeviceTemplateEdit();

//Check if addDeviceTemplateForm was submitted
if($devices->newDeviceTemplateFormSubmitted()) {
    if(!$devices->addNewDeviceTemplate($_POST["newdevicetemplateform_name"], $_POST["newdevicetemplateform_description"])) {
        $addNewDeviceTemplateError = true;
    }
}

//Check if newDeviceSingleForm was submitted
if($devices->newDeviceSingleFormSubmitted()) {
    if(!$devices->createSingleDevice(   $_POST["newDeviceSingleForm_devicetemplateid"],
                                        $_POST["newDeviceSingleForm_callsign"],
                                        $_POST["newDeviceSingleForm_serialnumber"],
                                        $_POST["newDeviceSingleForm_notes"])) {
        $newDeviceSingleError = true;
    } else {
        $_POST["newDeviceSingleForm_devicetemplateid"] = false;
        $_POST["newDeviceSingleForm_callsign"] = false;
        $_POST["newDeviceSingleForm_serialnumber"] = false;
        $_POST["newDeviceSingleForm_notes"] = false;
    }
}

//Check if newDeviceMultiForm was submitted
if($devices->newDeviceMultiFormSubmitted()) {
    if(!$devices->createMultiDevices($_POST["newDeviceMultiForm_devicetemplateid"], $_POST["newDeviceMultiForm_amount"])) {
        $newDeviceMultiError = true;
    } else {
        $_POST["newDeviceMultiForm_devicetemplateid"] = false;
        $_POST["newDeviceMultiForm_amount"] = false;
    }
}
?>
    <script src="<?=domain.dir_js?>devices.js"></script>
    <div class="content_wrapper">
        <div class="page_title">Devices</div>
        <hr class="header_spacer"/>
    <span style="display: inline-block; max-width: 600px;">
        This page gives you access to all device specific data like current available devices, device-templates and some stats.
    </span><br/>
        <br/>

    <span class="content_block">
        <div class="page_subtitle">Device Templates</div>
        <hr class="header_spacer"/>
        <?=$deviceTemplateFormEditMessage?>
        <?php
        $devices->printDeviceTemplateList();
        if($addNewDeviceTemplateError) { ?><div style="display: inline-block;" id="newdevicetemplate"><?php }
        else { ?><div style="display: none;" id="newdevicetemplate"><?php } ?>
            <form method="POST" action="<?=domain?>index.php?p=devices">
                <input type="hidden" name="newdevicetemplateform_submitted" value="true"/>
                <br/><b>New Devicetemplate</b>&nbsp;-&nbsp;<a href="#" onclick="despawnAddDeviceTemplateForm()">Cancel</a><br/>
                <input type="text" name="newdevicetemplateform_name" value="" placeholder="Device-Name" style="width: 200px; <?php if($addNewDeviceTemplateError) { echo "border: 1px solid #EE0000;"; } ?>" required/>
                <input type="text" name="newdevicetemplateform_description" value="" placeholder="Device-Description" style="width: 300px;"/>
                <input type="submit" value="Save" style="border: 1px solid #006357;" />
            </form>
        </div>
    </span>
    <br/><br/>
    <span class="content_block">
        <div class="page_subtitle">Registered Devices</div>
        <hr class="header_spacer"/>
        <div>Below you will find a list of all registered devices, sorted by name, callsign and then deviceid.</div>
        <?php $devices->generateRegisteredDevicesList($_POST["serachRegisteredDevices_field"], $_POST["serachRegisteredDevices_value"], $_POST["serachRegisteredDevices_availability"]); ?>
        <div style="display: none;" id="newDeviceSingleForm_wrapper">
            <br/>
            <form method="POST" action="<?=domain?>index.php?p=devices" id="newDeviceSingleForm">
                <input type="hidden" name="newDeviceSingleForm_submitted" value="true"/>
                <b>Create new device</b>&nbsp;-&nbsp;<a href="#" onclick="despawnNewDeviceSingleForm()">Cancel</a>
                <?php if($newDeviceSingleError) { ?><br/><b style="color: #EE0000;">Error, invalid input!</b><?php } ?>
                <table>
                    <tr>
                        <td style="text-align: right; font-size: 12px;">Devicetemplate</td>
                        <td style="text-align: left;">
                            <select name="newDeviceSingleForm_devicetemplateid" reqiured>
                                <option value="">--- Please select a template ---</option>
                                <?php foreach($devices->getDeviceTemplates() as $devicetemplate) {
                                    ?><option value="<?=$devicetemplate->{"devicetemplateid"}?>" <?php if($devicetemplate->{"devicetemplateid"}==$_POST["newDeviceSingleForm_devicetemplateid"]) { echo "selected"; } ?>><?=$devicetemplate->{"name"}?> (DTID: <?=$devicetemplate->{"devicetemplateid"}?>)</option><?php
                                } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-size: 12px;">Callsign</td>
                        <td style="text-align: left;"><input type="text" name="newDeviceSingleForm_callsign" value="<?=$_POST["newDeviceSingleForm_callsign"]?>" placeholder="1337" style="width: 100px;"/></td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-size: 12px;">Serialnumber</td>
                        <td style="text-align: left;"><input type="text" name="newDeviceSingleForm_serialnumber" value="<?=$_POST["newDeviceSingleForm_serialnumber"]?>" placeholder="D5451-84849-25481" style="width: 200px;"/></td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-size: 12px;">Notes</td>
                        <td style="text-align: left;"><input type="text" name="newDeviceSingleForm_notes" value="<?=$_POST["newDeviceSingleForm_notes"]?>" placeholder="Scratch on the back" style="width: 200px;"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: left;"><input type="submit" value="Create Device"/></td>
                    </tr>
                </table>
            </form>



        </div>
        <div style="display: none;" id="newDeviceMultiForm_wrapper">
            <br/>
            <form method="POST" action="<?=domain?>index.php?p=devices" id="newDeviceMultiForm">
                <input type="hidden" name="newDeviceMultiForm_submitted" value="true"/>
                <b>Create multiple devices</b>&nbsp;-&nbsp;<a href="#" onclick="despawnNewDeviceMultiForm()">Cancel</a>
                <?php if($newDeviceMultiError) { ?><br/><b style="color: #EE0000;">Error, invalid input!</b><?php } ?>
                <table>
                    <tr>
                        <td style="text-align: right; font-size: 12px;">Devicetemplate</td>
                        <td style="text-align: left;">
                            <select name="newDeviceMultiForm_devicetemplateid" reqiured>
                                <option value="">--- Please select a template ---</option>
                                <?php foreach($devices->getDeviceTemplates() as $devicetemplate) { ?><option value="<?=$devicetemplate->{"devicetemplateid"}?>"><?=$devicetemplate->{"name"}?> (DTID: <?=$devicetemplate->{"devicetemplateid"}?>)</option> <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-size: 12px;">Amount</td>
                        <td>
                            <input type="number" name="newDeviceMultiForm_amount" min="1" max="100" step="1" value="<?=$_POST["newDeviceMultiForm_amount"]?>" placeholder="1" style="width: 50px;"/>&nbsp;
                            <input type="submit" value="Create Devices"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </span>
    <br/>

<?php
if($newDeviceSingleError) { ?><script type="text/javascript">spawnNewDeviceSingleForm();</script><?php }
if($newDeviceMultiError) { ?><script type="text/javascript">spawnNewDeviceMultiForm();</script><?php }
require_once(basetpl_footer);
?>