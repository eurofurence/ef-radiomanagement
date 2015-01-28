<td class="navi_td">
    <a href="<?=domain?>index.php?p=overview"><img src="<?=domain.dir_img?>logo.png" /></a>
    <hr class="navi_spacer"/>
    <div class="navi_logininfo">
        <div style="width:100%; text-align:center;"><b>Login Information</b></div>
        Nickname:&nbsp;<?=$sessions->getUserName()?><br/>
        RegID:&nbsp;<?=$sessions->getRegId()?><br/>
        UserLevel:&nbsp;<?=$sessions->getUserLevelText()?><br/>
    </div>
    <hr class="navi_spacer"/>
    <?php
    switch($sessions->getUserLevel()) {
        case 1: require(dir_navis."navi_user.tpl.php"); break;
        case 2: require(dir_navis."navi_mod.tpl.php"); break;
        case 3: require(dir_navis."navi_admin.tpl.php"); break;
        default: break;
    }
    ?>
</td>
<td class="navi_spacer"></td>