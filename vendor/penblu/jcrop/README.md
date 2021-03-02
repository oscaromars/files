JCrop Extension
=================
Extension de Jcrop creada por PenBlu

Jcrop is the quick and easy way to add image cropping functionality to
your web application. It combines the ease-of-use of a typical jQuery
plugin with a powerful cross-platform DHTML cropping engine that is
faithful to familiar desktop graphics applications.

Reference Plugin: http://github.com/tapmodo/Jcrop


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist penblu/jcrop "*"
```

or add

```
"penblu/jcrop": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```
php
<?= \penblu\jcrop\JCrop::widget(); ?>
```