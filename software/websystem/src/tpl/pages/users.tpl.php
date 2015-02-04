<?php
require_once(basetpl_header);
require(basetpl_navi);
require_once(func_bindings);
$bindings = new bindings();
?>

<td class="content_td">
    <div class="page_title">Users</div>
    <hr class="header_spacer"/>

    <span class="content_block">This page gives you an access to all user related database entries.</span><br/>
    <br/>

    <span class="content_block">
        <div class="page_subtitle">Registered Users</div>
        <hr class="header_spacer"/>
        <?php $sessions->genreateUserList($_POST["usersSearchForm_field"], $_POST["usersSearchForm_value"]); ?>
    <br/>
</td>

<?php require_once(basetpl_footer); ?>