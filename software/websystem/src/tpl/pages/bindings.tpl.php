<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
$bindings = new bindings();
?>

<td class="content_td">
    <div class="page_title">Bindings</div>
    <hr class="header_spacer"/>
    <span class="content_block">This page gives you an overview of all active bindings. If you want to create a new bindings please use the <a href="<?=domain?>index.php?p=add_binding">Add Binding-Page</a>!</span><br/>
    <br/>

    <span class="content_block">
        <div class="page_subtitle">Active bindings</div>
        <hr class="header_spacer"/>
        <span class="content_block">The tabel below contains all active bindings and is searchable.</span><br/>
        <?php $bindings->generateBindingsList(false, false); ?>
        Klick <a href="<?=domain?>index.php?p=add_binding">here</a> to add a new binding!
    </span><br/>

</td>

<?php require_once(basetpl_footer); ?>