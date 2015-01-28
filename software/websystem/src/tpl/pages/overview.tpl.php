<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
$bindings = new bindings();
?>

<td class="content_td">
    <div class="page_title">Overview</div>
    <hr class="header_spacer"/>
    <span style="display: inline-block; max-width: 600px;">
        Hello <?=$sessions->getUserName()?>,<br/>
        <br/>
        within this system you can access your current assigned devices as well as all available callsigns. If you experience any kind of problems with this system, your radio or your accessories please contact <?=$core->getSetting("helpcallsign")?> for help ;)
    </span><br/>
    <br/>

    <span style="display: inline-block; min-width: 300px; max-width: 600px; vertical-align: top;">
        <div class="page_subtitle">Your Devices</div>
        <hr class="header_spacer"/>
        <?php $bindings->printUserBindings($sessions->getUserId()); ?>
    </span>
    <br/><br/>
    <span style="display: inline-block; min-width: 300px; max-width: 600px; vertical-align: top;">
        <div class="page_subtitle">Callsigns</div>
        <hr class="header_spacer"/>
        <?php $bindings->generateCallsignList(); ?>
    </span>
</td>

<?php require_once(basetpl_footer); ?>