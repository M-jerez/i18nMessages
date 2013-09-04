<?php
include_once 'i18nMessages.php';

$messages = new i18nMessages();
$messages->compile();

?>


<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div><?php p("hello world"); ?></div>
        <div><?php p("%s %s",'Hello','world'); ?></div>
    </body>
</html>

