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
        $addNewDevicesError = true;
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
        if($addNewDevicesError) {
            ?><div style="display: inline-block;" id="newdevicetemplate"><?php
        } else {
            ?><div style="display: none;" id="newdevicetemplate"><?php
        }
        ?>
            <form method="POST" action="<?=domain?>index.php?p=devices">
                <input type="hidden" name="newdevicetemplateform_submitted" value="true"/>
                <br/><b>New Devicetemplate</b>&nbsp;-&nbsp;<a href="#" onclick="despawnAddDeviceTemplateForm()">Cancel</a><br/>
                <input type="text" name="newdevicetemplateform_name" value="" placeholder="Device-Name" style="width: 200px; <?php if($addNewDevicesError) { echo "border: 1px solid #EE0000;"; } ?>" required/>
                <input type="text" name="newdevicetemplateform_description" value="" placeholder="Device-Description" style="width: 300px;"/>
                <input type="submit" value="Save" style="border: 1px solid #006357;" />
            </form>
        </div>
    </span>
    <br/><br/>
    <span class="content_block">
        <div class="page_subtitle">Registered Devices</div>
        <hr class="header_spacer"/>
        <div>Below you will find a list of all registered devices, sorted by name, callsign and then deviceid.</div><br/>
        <?php $devices->generateRegisteredDevicesList($_POST["serachRegisteredDevices_field"], $_POST["serachRegisteredDevices_value"], $_POST["serachRegisteredDevices_reset"]); ?>
    </span>
    <br/><br/>
    <span class="content_block">
        <div class="page_subtitle">Available Devices</div>
        <hr class="header_spacer"/>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque eveniet, facere illo ipsum obcaecati odio veniam. At deserunt esse, non placeat quod repellat reprehenderit soluta suscipit tempora totam, unde voluptates?
    </span>
    </div>

<?php require_once(basetpl_footer); ?>