<?php

require_once 'i18nMessages.php';
/*
 * Creates the i18n object with the 'fr' locale.
 * All messages will be tranlated to fr.
 */
i18nMessages::setLocale('fr');
//i18nMessages::setLocale('en');
//i18nMessages::setLocale('es');
//i18nMessages::setLocale('de');





?>



<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <div><?php p('hello world'); ?></div>
    </body>
</html>


