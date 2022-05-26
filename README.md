# SodasHelper plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require swri-sodas/sodas-helper
```

### Includes: 
#### Elements:
- Bootstrap toast
- File dropper element
- Read more element

#### TinyMCE configuration: 
- Sets for TinyMCE 6 and adds bootstrap-like styling
- Requires https://github.com/CakeDC/TinyMCE
- Replaces CkEditor in our sites

How to use:

```
composer require swri-sodas/sodas-helper
composer require cakedc/tiny-mce
```

In `Application.php`:

```php
$this->addPlugin('SodasHelper');
```
Then in the controller method, add:
```php
$this->viewBuilder()->addHelper('TinyMCE.TinyMCE');
```
In a template, on an input/textarea/etc, add class `timymce`
eg: `echo $this->Form->input('input', ['class' => 'tinymce']]);`

Lastly, at the bottom of the template file:
```php
$this->TinyMCE->editor();
```

#### Middlewares configuration:
- Set security headers
- HttpsEnforcerMiddleware
- CsrfProtectionMiddleware settings for https

#### TODO:
* start and end time
* SDDAS (generic plotting) - catalog/DB
* plotting (idl/python/sddas/java (autoplot)) - hpca/
* datatables
* spawning a long running process - geoviz/plotting
* ajax helper
