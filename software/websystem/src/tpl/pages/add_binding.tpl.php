<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
require_once(func_devices);
$bindings = new bindings();
$devices = new devices();
?>

<td class="content_td">
    <div class="page_title">Add Binding</div>
    <hr class="header_spacer"/>
    <span style="display: inline-block; max-width: 600px;">
        You can add new bindings on this page. Please create the device as well as the desired user first!
    </span><br/>
    <br/>
    <?php
        //Check if GET-Override for user-selection is active
        if(!$_POST["addBinding_searchUserForm_searchString"] && $_GET["searchUser_userid"]) {
            $_POST["addBinding_searchUserForm_submitted"] = true;
        }

        //Check for current form-state
        if($bindings->searchUserForm_submitted()) {
            $bindings->addBinding_selectUser($_POST["addBinding_searchUserForm_searchString"], $_GET["searchUser_userid"]);
        } elseif($bindings->searchDeviceForm_submitted()) {

        } else {
            unset($_SESSION["addBinding"]);
            $bindings->addBinding_printSearchUserForm(false);
        }
    ?>
</td>

<?php require_once(basetpl_footer); ?>