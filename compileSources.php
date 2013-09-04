<?php

include_once 'i18nMessages.php';
//creates the i18n object with default locale = 'en'.
$messages = new i18nMessages();

/* 
 * Creates translation files parsing the source code.
 * The system scans all directories from the current directory
 * where the i18nMessages.php file is located.
 */
$messages->compile();

/* 
 * Creates translation files parsing the source code.
 * The system scans all directories from the $rootDir (optional parameter).
 */
$rootDir = __DIR__;
$messages->compile($rootDir);

?>
