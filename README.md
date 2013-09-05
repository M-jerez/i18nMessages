# PHP i18n Messages inspired by gettext.

### Features.
1. Automatic generation of Translation-Files from source code.
2. Translations-Files are php Arrays. less friendly but doesn't need external tools, best performace than .ini
4. Shows default message if doesn't find a translation.
5. Works as print or printf.
6. Keep translated Messages when Traslation-Files are generated again.
7. Backup deleted or modified messages.

### Not Implemented.
* plurals
* contexts
* Only parse php source code.



### Usage.
```php
<?php
//import and set 'fr' locale. If omitted default locale is 'en'.
require_once 'i18nMessages.php';
i18nMessages::setLocale('fr');

//Sets the languages to work with.
i18nMessages::setlanguages(array('en','es','de','fr'));

//Sets the root directory where is located the languages folder.
i18nMessages::setRootDirectory($rootDir);
?>
```
```php
<!-- file : index.php -->
<html>
    <head></head>
    <body>
        <div>
        <?php
            p('hello world'); //Prints the translated message.
            $string = g('hello world'); //Returns the translated message.
        ?>
        </div>
        <div>
        <?php 
            p('hello world %s', 'Tony'); //Formats and Prints the translated message.
            $string = g('hello world %s', 'Tony'); //Formats and Returns the translated message.
        ?>
        </div>
    </body>
</html>
```
```php
// IMPORTANT: dont use variables when call the p() or g() functions.
$number = 12345;
p("item: $number"); // THIS IS NOT ALLOWED, throws fatal error when compile Translation-Files

p("item: %d", $number); // THIS IS CORRECT       
```
### Automatic Generation of Translation-Files.
```php
//import and init.
require_once 'i18nMessages.php';
$messages = new i18nMessages();

//Sets the languages to generate.
i18nMessages::setlanguages(array('en','es','de','fr'));

//Scan php files and generates the translation files. 
$messages->compile();

//Generates the Translation-Files scaning all .php files and subfolders, starting from the 'rootDir'. 
$messages->compile('rootDir');
```
### Generated Files & Directories.
![Generated Files](https://raw.github.com/M-jerez/i18nMessages/master/generated-files.png)

### //TODO : Html editor for Translation-Files.
It would be a great tool to make translation friendly for non programers.
