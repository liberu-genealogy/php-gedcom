# php-gedcom
 ![Latest Stable Version](https://img.shields.io/github/release/familytree365/php-gedcom.svg) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/familytree365/php-gedcom/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/familytree365/php-gedcom/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/familytree365/php-gedcom/badges/build.png?b=master)](https://scrutinizer-ci.com/g/familytree365/php-gedcom/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/familytree365/php-gedcom/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![StyleCI](https://github.styleci.io/repos/262784020/shield?branch=master)](https://github.styleci.io/repos/262784020)
[![CodeFactor](https://www.codefactor.io/repository/github/familytree365/php-gedcom/badge/master)](https://www.codefactor.io/repository/github/familytree365/php-gedcom/overview/master)
[![codebeat badge](https://codebeat.co/badges/911f9e33-212a-4dfa-a860-751cdbbacff7)](https://codebeat.co/projects/github-com-modularphp-gedcom-php-gedcom-master)
[![Build Status](https://travis-ci.org/familytree365/php-gedcom.svg?branch=master)](https://travis-ci.org/familytree365/php-gedcom)




## Requirements

* php-gedcom 1.0+ requires PHP 8.0 (or later).

## Installation

There are two ways of installing php-gedcom.

### Composer

To install php-gedcom in your project using composer, simply add the following require line to your project's `composer.json` file:

    {
        "require": {
            "familytree365/php-gedcom": "1.0.*"
        }
    }

### Download and __autoload

If you are not using composer, you can download an archive of the source from GitHub and extract it into your project. You'll need to setup an autoloader for the files, unless you go through the painstaking process if requiring all the needed files one-by-one. Something like the following should suffice:

```php
spl_autoload_register(function ($class) {
    $pathToGedcom = __DIR__ . '/library/'; // TODO FIXME

    if (!substr(ltrim($class, '\\'), 0, 7) == 'Gedcom\\') {
        return;
    }

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($pathToGedcom . $class)) {
        require_once($pathToGedcom . $class);
    }
});
```

### Usage

To parse a GEDCOM file and load it into a collection of PHP Objects, simply instantiate a new Parser object and pass it the file name to parse. The resulting Gedcom object will contain all the information stored within the supplied GEDCOM file:

```php
$parser = new \Gedcom\Parser();
$gedcom = $parser->parse('tmp.ged');

foreach ($gedcom->getIndi() as $individual) {
    echo $individual->getId() . ': ' . current($individual->getName())->getSurn() .
        ', ' . current($indi->$individual())->getGivn();
}
```
