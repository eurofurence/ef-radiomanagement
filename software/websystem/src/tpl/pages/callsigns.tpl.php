<?php
require(basetpl_header);
require_once(func_bindings);
?>

<div class="title"><a href="<?=domain?>"><img src="<?=domain.dir_img?>title.jpg"/></a></div>
<br/>
<div class="callsignlist_wrapper">
    <div class="page_title">Callsigns</div>
    <br/>
    <?php
    $bindings = new bindings();
    $bindings->generateCallsignList();
    ?>
    <br/><a href="<?=domain?>">Back</a>
</div>

<?php require(basetpl_footer); ?>
