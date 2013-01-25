Password Hashing Bad Web Application
====================================

Note, this is a **BAD WEB APP**. Meaning that it has known vulnerabilities in it for the purpose of education.
DO NOT USE THIS CODE, OR ANY DERIVATION OF IT FOR ANYTHING OTHER THAN EDUCATION.

This repository goes along with the talk that I did at PHP Benelux 2013: [Password Storage and Attacking in PHP](http://www.slideshare.net/ircmaxell/password-storage-and-attacking-in-php)

## Installation

Checkout the repo

1. Download Composer:

        $ curl -s https://getcomposer.org/installer | php

2. In the root of the checkout

        $ php composer.phar install

## Use
Setup a web server to point to /public, and then go to /. It expects to be in the root of the domain, so just create a new local domain name (like `password.local`). 

After updating the password hashing implementation in `/lib/password.php`, run `php update.php` from the command line to rewrite the default users with the new hash method.

## Branches
Various password hashing implementations are included:

* `plaintext` - Stores plaintext passwords
* `md5` - Stores passwords as a plain `md5()` hash
* `salted-md5` - Stores passwords as a salted md5 hash
* `hmac-salted-md5` - An HMAC based salted md5 hash
* `iterated-md5` - An iterated version of the HMAC md5 hash
* `pbkdf2` - Uses the `pbkdf2()` algorithm with sha512
* `bcrypt` - Uses the `crypt()` library directly, with bcrypt
* `password-compat` - Uses the new 5.5 API with bcrypt
* `bcrypt-with-encryption` - Uses the new 5.5 API, encrypting the storage with Zend\Crypt

The users are pre-updated, so you don't need to run update after checking out the branch.

Enjoy!
