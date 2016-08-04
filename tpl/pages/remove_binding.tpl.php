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
require_once(func_devices);
$devices = new devices();
$bindings = new bindings();
?>

    <td class="content_td">
        <div class="page_title">Remove Binding</div>
        <hr class="header_spacer"/>
    <span style="display: inline-block; max-width: 600px;">
        You can remove bindings with a guided wizard on this page.
    </span><br/>
        <br class="small"/>

        <?php
            if($_POST['removeBinding_searchDeviceForm_submitted']) {
                //FIXME: Implement device based search
            } elseif($_POST['removeBinding_searchUserForm_submitted'] || $_GET['findUser_userid']) {
                // Check if userid-override is active
                if ($_GET['findUser_userid']) {
                    $bindings->removeBinding_findUser(false, $_GET['findUser_userid']);
                } else {
                    $bindings->removeBinding_findUser($_POST['removeBinding_searchUserForm_searchString'], false);
                }
            } elseif($_GET['removeAllByUID']) {
                $bindings->removeBinding_removeAllBindingsFromUser($_GET['removeAllByUID']);
            } else {
                $bindings->removeBinding_printSearchUserForm(false);
            }
        ?>

    </td>

<?php require_once(basetpl_footer); ?>