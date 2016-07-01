<!-- Big navi at the left -->
<td class="navi_td navi_big">
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
<td class="navi_spacer navi_big"></td>

<!-- Small navi at the top -->
<span class="navi_small">
    <span class="navi_small_topbar">
        <a class="navi_small_logo" href="<?=domain?>">
            <img class="navi_small_title" src="<?=domain.dir_img?>title_split_logo.jpg"/>
            <img class="navi_small_title navi_small_title_text" src="<?=domain.dir_img?>title_split_text.jpg"/>
        </a>
        <a class="navi_small_opener" style="color:#ffffff;" href="#"><img src="<?=domain.dir_img?>menu.png"/></a><br/>
    </span>
    <span class="navi_small_menu">
        <?php
        switch($sessions->getUserLevel()) {
            case 1: require(dir_navis."navi_user.tpl.php"); break;
            case 2: require(dir_navis."navi_mod.tpl.php"); break;
            case 3: require(dir_navis."navi_admin.tpl.php"); break;
            default: break;
        }
        ?>
    </span>
</span>
<td class="navi_spacer_pocketpc navi_small"></td>