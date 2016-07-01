<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2016 Niels Gandraß <ngandrass@squacu.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
?>

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