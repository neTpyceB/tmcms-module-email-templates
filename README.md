# tmcms-module-email-templates
Module Email Templates for The Modern Cms

Module adds ability to create and send editable templates for emails. To use it in code:
```php
$params = ['username' => 'Vasja', 'age' => 28];
$email = ['admin@example.com', 'client@email.com'];
ModuleEmailTemplates::send('new_registration', $params, $emails);
```
This will send template with key "new_registration" and replaced parameters `$params` to all email supplied in `$emails`. Params are used with `{%param%}` syntax.
