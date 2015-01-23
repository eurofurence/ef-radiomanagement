<?php
    $sessions->logout();
    header("Location: ".domain."index.php?p=index");
?>