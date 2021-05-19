BlockUi Extension
=================
Extension blockui creada por PenBlu

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist penblu/barcode "*"
```

or add

```
"penblu/barcode": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \penblu\barcode\GeneratedCodebar::widget(); ?>```
