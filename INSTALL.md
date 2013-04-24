Gridguyz - CMS: Install
=======================

1. [System requirements](#system-requirements)

   1. [Database](#database)
   2. [Web server](#web-server)
   3. [PHP](#php)
   4. [Composer](#composer)

2. [Installation](#installation)

   1. [With composer](#with-composer)
   2. [With git clone](#with-git-clone)
   3. [Add modules](#add-modules)

System requirements
-------------------

### Database

Gridguyz runs on [PostgreSQL](http://www.postgresql.org/download/) 9.2+

### Web server

Gridguyz needs a web-server (HTTP server) with a `mod_rewrite`-like extension.

Tested HTTP servers:
* [Apache](http://httpd.apache.org/download.cgi) 2.2+
* [Nginx](http://nginx.org/en/download.html)
* [Lighttpd](http://www.lighttpd.net/download/) / *lighty* 1.4.24+ (soon)

Server docroot should be set under `$gridguyz_path/public`.

### PHP

Gridguyz runs on PHP 5.4+

Must enable these bundled extensions:
* `gd2`
* `zip`
* `curl`
* `intl`
* `fileinfo`
* `mbstring`
* `openssl`
* `pdo_pgsql`

Should enable these extensions:
* `apc`
* `bz2`
* `xmlrpc`

### Composer

[Composer](http://getcomposer.org/download/) is a package manager for PHP.
All gridguyz modules are in composer packages.

Installation
------------

### With composer

You can install Gridguyz with composer <sup>[1](#--no-custom-installers)</sup>

```sh
$ php composer.phar create-project webriq/gridguyz-cms
```

### With git clone

1.  Clone the Gridguyz skeleton application

    ```sh
    $ git clone git://github.com/webriq/gridguyz-cms.git
    ```

2.  Install packages with composer <sup>[1](#--no-custom-installers)</sup>

    ```sh
    $ php composer.phar install
    ```

### Add modules

You can add modules to your Gridguyz instance by simply runnig

```sh
$ php composer.phar require
```

<a name="--no-custom-installers"></a><sup id="--no-custom-installers">1</sup>
You should not use the `--no-custom-installers` flag,
as Gridguyz use custom composer package-types.
