# i18n-messages, PHP Internationalization inspired by gettext

##Features


### import and init i18n with 'fr' locale. If omitted default locale is 'en'.
```php
require_once 'i18nMessages.php';
$message = new i18nMessages('fr');
```
### Scans all php files and generates the translation files.
```php
$messages->compile();
```