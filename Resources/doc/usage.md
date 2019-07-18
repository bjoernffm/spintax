<!---
# This file is part of the ChillDev Spintax library.
#
# @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
# @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
# @version 0.0.1
# @since 0.0.1
# @package ChillDev\Spintax
-->

# Usage

## Using parsed spintax with Content class

First step to work with spintax is to parse it with a parser:

```php
use ChillDev\Spintax\Parser;

$source = 'I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.';
$spintax = Parser::parse($source);
```

And from now let's assume this source and `$spintax` (which is object representing parsed source) in all further examples.

### Producing variations

First of all you probably want to use this object to produce some possible variations of the source spintax. You can generate random variant with:

```php
$variant = $spintax->generate();
```

Just like that.

#### Paths

However each call to `Content::generate()` will pick a random variant. You can use paths to define particular steps to pick. Path consists of numbers defining which option from each set to choose (counted from 0). Each nexxt step is used for next choice.

Lets consider our sample source:

```
I
|-- love            0
|   |-- PHP         |-- 0
|   |-- Java        |-- 1
|   |-- C           |-- 2
|   |-- C++         |-- 3
|   |-- JavaScript  |-- 4
|   `-- Python      `-- 5
`-- hate Ruby       1
```

So for example _I love PHP_ is path `[0, 0]`, _I love JavaScript_ is `[0, 4]`, _I hate Ruby_ is `[1]`.

Note that path length can differ depending on steps you tak (on some paths there can be more levels then on anothers).

You can use path as an argument for `Content::generate()` (this argument needs to be passed by reference, so you need to use variable for that):

```php
$path = [0, 0];
$iLovePhp = $spintax->generate($path);
```

The example above will always generate _I love PHP_ variant.

Why is it a reference? Because of the second use of it - it can be used not only to pick the variant, but also to retrive path that was used to generate random one. If you don't have a path and you want to generate random variant, but want to keep the path, you can use the same argument:

```php
$path = [];
$variant = $spintax->generate($path);
echo $variant, ' has path ', implode(',', $path);
```

##### Incomplete paths

Paths don't need to be "complete" - they don't need to specify all steps. You can define only some starting steps and all next steps will be picked randomly. Also the passed variable will still be filled with complete set of steps used to finish variant generation:

```php
$path = [0];
$randomILoveVariant = $spintax->generate($path);
echo $variant, ' has path ', implode(',', $path);
```

The example above will only generate variants that starts from _I love_.

### Dealing with source

Additionally `Content` class instance contains all the information about the source form. Here are some additional methods that you can find useful:

-   `getPaths()` returns list of all possible paths (array of arrays);
-   `count()` returns number of possible variants (`Content` class implements `Countable` interface, so you can just do `count($spintax)`);
-   `dump()` returns source spintax form (`Content` class implements `__toString()` that handles casting to string type - it internally calls `dump()` method).

### Dumping all possible variations

After we have all this info let's see a short example of how to dump all possible variations:

```php
use ChillDev\Spintax\Parser;

$source = 'I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.';
$spintax = Parser::parse($source);

foreach ($spintax->getPaths() as $path) {
    echo $spintax->generate($path), "\n";
}
```

## Using Parser::replicate()

Apart from all these direct operation, you can also use another, shortcut method `Parser::replicate()`:

```php
use ChillDev\Spintax\Parser;

$source = '{Hi|Hello} {World|people}!';
$spintax = Parser::parse($source);

// all of these print "Hello World!"
echo Parser::replicate($source, [1, 0]);
echo Parser::replicate($source, '1,0');
echo Parser::replicate($spintax, [1, 0]);
echo Parser::replicate($spintax, '1,0');
```

There are four important differences between `Parser::replicate()` and using `Content` class API:

-   first argument can be either plain string or already parsed `Content` isntance;
-   second argument may either be an array path, or a string path which consists of steps separated by commas;
-   second parameter is not passed by reference so it will not be modified;
-   second parameter is mendatory, but you still can provideincomplete paths, notable an empty array also.
