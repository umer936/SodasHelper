# SodasHelper plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require swri-sodas/sodas-helper
```

### Includes: 
Elements:
- Bootstrap toast
- File dropper element
- Read more element

TinyMCE configuration: 
- Sets for TinyMCE 6 and adds bootstrap-like styling
- Requires https://github.com/CakeDC/TinyMCE
- Replaces CkEditor in our sites

Middlewares configuration:
- Set security headers
- HttpsEnforcerMiddleware
- CsrfProtectionMiddleware settings for https

TODO:
* start and end time
* SDDAS (generic plotting) - catalog/DB
* plotting (idl/python/sddas/java (autoplot)) - hpca/
* datatables
* spawning a long running process - geoviz/plotting
