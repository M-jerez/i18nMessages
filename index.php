<?php

require_once 'i18nMessages.php';
/*
 * Creates the i18n object with the 'fr' locale.
 * All messages will be tranlated to fr.
 */
$messages = new i18nMessages('fr');

?>


<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div><?php p('hello world'); ?></div>
        <div><?php p("%s %s",'Hello','world'); ?></div>
    </body>
</html>


