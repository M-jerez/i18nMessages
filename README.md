# PHP i18n Messages inspired by gettext.

### Features.
1. Autogeneration of Translation-Files from source code.
2. Translations-Files are php Arrays. (less friendly, doesn't need external tools, best performace than ini files)
4. Shows default message if doesn't find a translation.
5. Works as print or printf.
6. Keep translated Messages when generates Traslation-Files again.
7. Backup deleted messages and it's translation to use it later.

### Not Implemented.
* plurals
* contexts



### Usage.
```php
<?php
//import and init with 'fr' locale. If omitted default locale is 'en'.
require_once 'i18nMessages.php';
$messages = new i18nMessages('fr');

//Sets all languages to work with.
$messages->setlanguages(array('en','es','de'));
?>
```
```html
<!-- file : index.php -->
<html>
    <head></head>
    <body>
        <div>
        <?php
            p('hello world'); //works as print but translates the message
        ?>
        </div>
        <div>
        <?php 
            p('hello world %s', 'Tony'); //works as printf but translaes the message
        ?>
        </div>
    </body>
</html>
```
```php
// IMPORTANT: dont use variables when call the p() function
$number = 12345;
p("item: $number"); // THIS IS NOT ALLOWED, throws fatal error when compile Translation-Files

p("item: %d", $number); // THIS IS CORRECT
       
```
### Automatic Generation of Translation-Files.
```php
//import and init.
require_once 'i18nMessages.php';
$messages = new i18nMessages();

//Scan php files and generates the translation files. 
$messages->compile();

//Generates the translation files starting from the 'rootDir'. 
$messages->compile('rootDir');
```

### //TODO : Html editor for Translation-Files.
It would be a great tool to make translation friendly for non programers.
