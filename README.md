# Spintax

![GitHub](https://img.shields.io/github/license/bjoernffm/step-functions)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/bjoernffm/step-functions)
![GitHub top language](https://img.shields.io/github/languages/top/bjoernffm/step-functions)
[![Build Status](https://travis-ci.org/bjoernffm/spintax.svg?branch=master)](https://travis-ci.org/bjoernffm/spintax)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d8ccfe0e2ce0401ba371095624461f74)](https://www.codacy.com/manual/bjoernffm/spintax)
[![StyleCI](https://github.styleci.io/repos/197543792/shield?branch=master&style=flat)](https://github.styleci.io/repos/197543792)

**Spintax** is a library which offers implementation of some commonly used patterns used in **Symfony2** DI.

## Installation

This library is provided as [Composer package](https://packagist.org/packages/bjoernffm/spintax). To install it simply execute the following command:

```plain
composer require bjoernffm/spintax
```

**Note:** This library requires **PHP 7.2+**.

## Usage

The simplest usage that will mostly fulfill your needs is to simple parse the spintax string and generate random variation of it:

```php
use bjoernffm\Spintax\Parser;

$spintax = Parser::parse('Schrödinger’s Cat is {dead|alive}.');
$string = $spintax->generate();
```

But there is much more that than that in our library. First of all nested structures are supported:

```php
use bjoernffm\Spintax\Parser;

$spintax = Parser::parse('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.');
$string = $spintax->generate();
```

Still not finished! With our brilliant library you can detect the path used to generate given variant and re-use it later:

```php
use bjoernffm\Spintax\Parser;

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
use bjoernffm\Spintax\Parser;

$path = [0];

$spintax = Parser::parse('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.');
// this will generate one of "I love {}." variants
$string = $spintax->generate($path);
```

For all this there is a shortcut method `Parser::replicate()` (you can use comma-separated number in a single string as second argument in this shortcut method):

```php
use bjoernffm\Spintax\Parser;

echo Parser::replicate('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.', '0,0');
```

For more advanced aspects see [advanced usage documentation](https://github.com/chilloutdevelopment/ChillDevSpintax/tree/master/Resources/doc/usage.md) or even [internals description](https://github.com/chilloutdevelopment/ChillDevSpintax/tree/master/Resources/doc/internals.md).

## Resources

-   [Source documentation](https://github.com/bjoernffm/spintax/blob/master/Resources/doc/index.md)
-   [GitHub page with API documentation](https://github.com/bjoernffm/spintax)
-   [Issues tracker](https://github.com/bjoernffm/spintax/issues)
-   [Packagist package](https://packagist.org/packages/bjoernffm/spintax)
-   [Development @ GitHub](https://github.com/bjoernffm)

## Contributing

Do you want to help improving this project? Simply *fork* it and post a pull request. You can do everything on your own, you don't need to ask if you can, just do all the awesome things you want!

This project is published under [MIT license](https://github.com/bjoernffm/spintax/blob/master/LICENSE).
