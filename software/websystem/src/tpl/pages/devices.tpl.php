<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
require_once(func_devices);
$bindings = new bindings();
$devices = new devices();

//Check if deviceTemplateEditForm was submitted
$deviceTemplateFormEditMessage = $devices->proccessDeviceTemplateEdit();
?>
    <script src="<?=domain.dir_js?>devices.js"></script>
    <div class="content_wrapper">
        <div class="page_title">Devices</div>
        <hr class="header_spacer"/>
    <span style="display: inline-block; max-width: 600px;">
        This page gives you access to all device specific data like current available devices, device-templates and some stats.
    </span><br/>
        <br/>

    <span style="display: inline-block; min-width: 300px; max-width: 600px; vertical-align: top;">
        <div class="page_subtitle">Device Templates</div>
        <hr class="header_spacer"/>
        <?=$deviceTemplateFormEditMessage?>
        <?php $devices->printDeviceTemplateList(); ?>
    </span>
    <br/><br/>
    <span style="display: inline-block; min-width: 300px; max-width: 600px; vertical-align: top;">
        <div class="page_subtitle">Registered Devices</div>
        <hr class="header_spacer"/>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque eveniet, facere illo ipsum obcaecati odio veniam. At deserunt esse, non placeat quod repellat reprehenderit soluta suscipit tempora totam, unde voluptates?
    </span>
    <br/><br/>
    <span style="display: inline-block; min-width: 300px; max-width: 600px; vertical-align: top;">
        <div class="page_subtitle">Available Devices</div>
        <hr class="header_spacer"/>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque eveniet, facere illo ipsum obcaecati odio veniam. At deserunt esse, non placeat quod repellat reprehenderit soluta suscipit tempora totam, unde voluptates?
    </span>
    </div>

<?php require_once(basetpl_footer); ?>