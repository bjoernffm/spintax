<!---
# This file is part of the ChillDev Spintax library.
#
# @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
# @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
# @version 0.0.1
# @since 0.0.1
# @package ChillDev\Spintax
-->

# ChillDev Spintax

**ChillDevSpintax** is a library which offers implementation of some commonly used patterns used in **Symfony2** DI.

[![Build Status](https://travis-ci.org/chilloutdevelopment/ChillDevSpintax.png)](https://travis-ci.org/chilloutdevelopment/ChillDevSpintax)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/chilloutdevelopment/ChillDevSpintax/badges/quality-score.png?s=2db247278a9b22e9d22e14f94a5d5fc4dc826f00)](https://scrutinizer-ci.com/g/chilloutdevelopment/ChillDevSpintax/)
[![Coverage Status](https://coveralls.io/repos/chilloutdevelopment/ChillDevSpintax/badge.png?branch=develop)](https://coveralls.io/r/chilloutdevelopment/ChillDevSpintax)
[![Dependency Status](https://www.versioneye.com/user/projects/52e1a8a1ec13751a000001a5/badge.png)](https://www.versioneye.com/user/projects/52e1a8a1ec13751a000001a5)

# Installation

This library is provided as [Composer package](https://packagist.org/packages/chilldev/spintax). To install it simply add following dependency definition to your `composer.json` file:

```
"chilldev/spintax": "dev-master"
```

Replace `dev-master` with different constraint if you want to use specific version.

**Note:** This library requires **PHP 5.4**.

# Usage

The simplest usage that will mostly fulfill your needs is to simple parse the spintax string and generate random variation of it:

```php
use ChillDev\Spintax\Parser;

$spintax = Parser::parse('Schrödinger’s Cat is {dead|alive}.');
$string = $spintax->generate();
```

But there is much more that than that in our library. First of all nested structures are supported:

```php
use ChillDev\Spintax\Parser;

$spintax = Parser::parse('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.');
$string = $spintax->generate();
```

Still not finished! With our brilliant library you can detect the path used to generate given variant and re-use it later:

```php
use ChillDev\Spintax\Parser;

$path = [];

$spintax = Parser::parse('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.');
// since $path is empty, random values will be used for missing indices and $path will be filled with them
$string = $spintax->generate($path);

// from now you can use $path to re-create the same combination
// all these calls will keep returning same string value
$spintax->generate($path);
$spintax->generate($path);
$spintax->generate($path);
$spintax->generate($path);

// this will force generating "I love Java."
$path = [0, 1];
$spintax->generate($path);
```

Paths are counted from 0, each entry is next step.

You can also use partial paths to define just the starting path and all missing parts will be choosen randomly:

```php
use ChillDev\Spintax\Parser;

$path = [0];

$spintax = Parser::parse('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.');
// this will generate one of "I love {}." variants
$string = $spintax->generate($path);
```

For all this there is a shortcut method `Parser::replicate()` (you can use comma-separated number in a single string as second argument in this shortcut method):

```php
use ChillDev\Spintax\Parser;

echo Parser::replicate('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.', '0,0');
```

For more advanced aspects see [advanced usage documentation](https://github.com/chilloutdevelopment/ChillDevSpintax/tree/master/Resources/doc/usage.md) or even [internals description](https://github.com/chilloutdevelopment/ChillDevSpintax/tree/master/Resources/doc/internals.md).

# Resources

-   [Source documentation](https://github.com/chilloutdevelopment/ChillDevSpintax/tree/master/Resources/doc/index.md)
-   [GitHub page with API documentation](http://chilloutdevelopment.github.io/ChillDevSpintax)
-   [Issues tracker](https://github.com/chilloutdevelopment/ChillDevSpintax/issues)
-   [Packagist package](https://packagist.org/packages/chilldev/spintax)
-   [Chillout Development @ GitHub](https://github.com/chilloutdevelopment)
-   [Chillout Development @ Facebook](http://www.facebook.com/chilldev)

# Contributing

Do you want to help improving this project? Simply *fork* it and post a pull request. You can do everything on your own, you don't need to ask if you can, just do all the awesome things you want!

This project is published under [MIT license](https://github.com/chilloutdevelopment/ChillDevSpintax/LICENSE).

# Authors

**ChillDevSpintax** is brought to you by [Chillout Development](http://chilldev.pl).

List of contributors:

-   [Rafał "Wrzasq" Wrzeszcz](https://github.com/rafalwrzeszcz) ([wrzasq.pl](http://wrzasq.pl)).
