<?php if($sessions->getUserLevel()>0) { header("Location: ".domain."index.php?p=overview"); } ?>
<?php require(basetpl_header); ?>

<td class="public_title">
    <div class="title"><a href="<?=domain?>"><img class="titleBanner" src="<?=domain.dir_img?>title.jpg"/></a></div>
    <br/>
    <div style="width: 100%; text-align: center;">If you need any help with your radio and/or this system call <?=$core->getSetting("helpcallsign")?>!</div>
    <div style="width: 100%; text-align: center;">
        <span class="index_buttons">
            <div class="index_big_button">
                <a class="index_big_button" href="<?=domain?>index.php?p=callsigns">
                    <div>Callsigns</div>
                </a>
            </div>
        </span>
        <span class="index_buttons">
            <div class="index_big_button">
                <a class="index_big_button" href="<?=domain?>index.php?p=login">
                    <div>Login</div>
                </a>
            </div>
        </span>
    </div>
</td>

<?php require(basetpl_footer); ?>