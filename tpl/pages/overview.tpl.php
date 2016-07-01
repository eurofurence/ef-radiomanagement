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

    <span class="content_block">
        <div class="page_subtitle">Your Devices</div>
        <hr class="header_spacer"/>
        <?php $bindings->printUserBindings($sessions->getUserId()); ?>
    </span>
    <br/><br/>
    <span class="content_block">
        <div class="page_subtitle">Callsigns</div>
        <hr class="header_spacer"/>
        <?php $bindings->generateCallsignList(); ?>
    </span>
    <br/><br/>
</td>

<?php require_once(basetpl_footer); ?>