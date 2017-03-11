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

Deploy these files on /var/www/html. Put this file in /var/www/html/web/.htaccess:

```
<IfModule mod_rewrite.c>
    Options -MultiViews

    RewriteEngine On
    RewriteBase app
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

Modify apache configuration so that it serves /var/www/html/web/.htaccess. This
can be done for example on the /etc/apache2/sites-enabled/000-default.conf:

```
<VirtualHost *:80>
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/html/web
	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Restart apache.

Testing
=======

```
phpunit tests/
```
