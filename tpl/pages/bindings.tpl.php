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

//Check if deleteBindingForm was submitted
if($bindings->deleteBindingFormSubmitted()) {
    $bindings->deleteBinding($_POST["deleteBindingForm_bindingid"]);
}

//Check if GET-Search is desired
if($_GET["sfield"] && $_GET["svalue"]) {
    $_POST["bindingListSearchForm_field"] = $_GET["sfield"];
    $_POST["bindingListSearchForm_value"] = $_GET["svalue"];
}
?>

<td class="content_td">
    <script src="<?=domain.dir_js?>bindings.js"></script>
    <div class="page_title">Bindings</div>
    <hr class="header_spacer"/>
    <span class="content_block">This page gives you an overview of all active bindings. If you want to create a new bindings please use the <a href="<?=domain?>index.php?p=add_binding">Add Binding-Page</a>!</span><br/>
    <br/>

    <span class="content_block">
        <div class="page_subtitle">Active bindings</div>
        <hr class="header_spacer"/>
        <?php
            if(!pocketPc) { ?><span class="content_block">The tabel below contains all active bindings and is searchable.</span><br/><?php }
            else { ?><span class="content_block">Enter your desired search-criteria to display active bindings.</span><br/><?php }
        ?>
        <?php $bindings->generateBindingsList($_POST["bindingListSearchForm_field"], $_POST["bindingListSearchForm_value"]); ?>
        Klick <a href="<?=domain?>index.php?p=add_binding">here</a> to add a new binding!
    </span><br/>

    <div style="display: none;">
        <form method="POST" action="<?=domain?>index.php?p=bindings&sfield=<?=$_POST["bindingListSearchForm_field"]?>&svalue=<?=$_POST["bindingListSearchForm_value"]?>" id="deleteBindingForm">
            <input type="hidden" name="deleteBindingForm_submitted" value="true"/>
            <input type="hidden" name="deleteBindingForm_bindingid" id="deleteBindingForm_bindingid" value=""/>
        </form>
    </div>
</td>

<?php require_once(basetpl_footer); ?>