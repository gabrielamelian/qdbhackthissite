Install dependencies
====================

```
composer install
```

Configure database
==================

Copy `config.php.orig` into `config.php` and fill in the details. 

Deploying on Apache
===================

This application requires mod_rewrite in order to create nicer URLs. The `web`
folder needs to be served, the rest of the files have to be outside the
webroot.

Alternatively for easier setup, this file can be put on the webroot:

```
DirectoryIndex qdbhackthissite/web/index.php
FallbackResource /qdbhackthissite/web/index.php
```

Testing
=======

```
phpunit tests/submit.php
```
