#paulbunyannet/autoversion

[![Build Status](https://travis-ci.org/paulbunyannet/autoversion.svg?branch=master)](https://travis-ci.org/paulbunyannet/autoversion)
[![Latest Version](https://img.shields.io/packagist/v/paulbunyannet/autoversion.svg?style=flat-square)](https://packagist.org/packages/paulbunyannet/autoversion)

**paulbunyannet/autoversion** Cache busting mechanisim for front end assets, used with the cache busting mechanism provided by the 
HTML5 Boilerplate Apache [.htaccess configuration](https://github.com/h5bp/server-configs-apache/blob/master/dist/.htaccess#L968-L984).

## Installation

In your terminal, just run:

```bash
composer require "paulbunyannet/autoversion":"~1.0"
```

## Configuration

This package is framework agnostic, the configuration process is:

```php
// Auto-load composer packages
use Pbc\AutoVersion\AutoVersion;
require 'vendor/autoload.php';

// Create new AutoVersion object and configure the document root
$auto = new AutoVersion($_SERVER['DOCUMENT_ROOT']);
```

Add to your .htaccess file, before any other routing mod rewrites

```
<IfModule mod_rewrite.c>
     RewriteEngine On
     RewriteCond %{REQUEST_FILENAME} !-f
     RewriteRule ^(.+)\.(\d+)\.(bmp|css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest)$ $1.$3 [L]
</IfModule>
```

## Usage

In your views, just call:

```php
// $pathToAsset is relative to the document root configured above, 
$auto->file($pathToAsset);
```

for example:

```php
<link rel="stylesheet" href="<?=$auto->file('/css/main.css') ?>">
<script src="<?=$auto->file('/js/main.js') ?>"></script>
```

which will output file names with their modified time appended to file name:

```
<link rel="stylesheet" href="/css/main.1234567890.css">
<script src="/js/main.1234567890.js"></script>
```
