<?php if($sessions->getUserLevel()>0) { header("Location: ".domain."index.php?p=overview"); } ?>
<?php require(basetpl_header); ?>

    <div class="title"><a href="<?=domain?>"><img src="<?=domain.dir_img?>title.jpg"/></a></div>
<br/>
<div style="width: 100%; text-align: center;">If you need any help with your radio and/or this system call <?=$core->getSetting("helpcallsign")?>!</div>
<div class="index_buttons">
    <span class="index_big_button">
        <a class="index_big_button" href="<?=domain?>index.php?p=callsigns">
            <div>Callsigns</div>
        </a>
    </span>
    <span class="index_big_button">
        <a class="index_big_button" href="<?=domain?>index.php?p=login">
            <div>Login</div>
        </a>
    </span>
</div>

<?php require(basetpl_footer); ?>