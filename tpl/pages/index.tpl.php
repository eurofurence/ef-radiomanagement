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

if($sessions->getUserLevel()>0) {
    header("Location: ".domain."index.php?p=overview");
}

require(basetpl_header);
?>


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