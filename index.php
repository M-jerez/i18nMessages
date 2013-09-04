<?php
include_once 'i18nMessages.php';
i18nMessages::setLocale('es');
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
        <div><?php p('hello world 5'); ?></div>
        <div><?php p("%s %s",'Hello','world'); ?></div>
    </body>
</html>


