<html>
    <head>
        <title><?=$core->getSetting("sitetitle")?></title>
        <link rel="stylesheet" type="text/css" href="<?=dir_css."main.css"?>"/>
        <?php
            if(strpos($_SERVER["HTTP_USER_AGENT"], "Windows Mobile") !== false) {
                define("pocketPc", true);
                ?><link rel="stylesheet" type="text/css" href="<?=dir_css."pocketpc.css"?>"/><?php
            } else { define("pocketPc", false); }
        ?>
        <link rel="icon" href="<?=domain.dir_img?>favicon.png" type="image/png" />
        <meta name="viewport" content="width=device-width">
    </head>
    <body>
        <table class="content_wrapper">
            <tr>