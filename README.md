# BanThis - A censor and filtering tool for PHP 8.2+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/diego-ninja/banthis.svg?style=flat&color=blue)](https://packagist.org/packages/diego-ninja/banthis)
[![Total Downloads](https://img.shields.io/packagist/dt/diego-ninja/banthis.svg?style=flat&color=blue)](https://packagist.org/packages/diego-ninja/banthis)
![PHP Version](https://img.shields.io/packagist/php-v/diego-ninja/cosmic.svg?style=flat&color=blue)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
![GitHub last commit](https://img.shields.io/github/last-commit/diego-ninja/banthis?color=blue)
[![Hits-of-Code](https://hitsofcode.com/github/diego-ninja/banthis?branch=main&label=Hits-of-Code)](https://hitsofcode.com/github/diego-ninja/laravel-devices/view?branch=main&label=Hits-of-Code&color=blue)
[![wakatime](https://wakatime.com/badge/user/bd65f055-c9f3-4f73-92aa-3c9810f70cc3/project/94491bff-6b6c-4b9d-a5fd-5568319d3071.svg)](https://wakatime.com/badge/user/bd65f055-c9f3-4f73-92aa-3c9810f70cc3/project/94491bff-6b6c-4b9d-a5fd-5568319d3071)

BanThis is a PHP package for profanity filtering. The PHP script uses regex to intelligently look for "leetspeak"-style numeric or symbol replacements.

This package is an evolution of [snipe/banbuilder](https://github.com/snipe/banbuilder) adapted and refactored to modern php versions.

## 📦 Installation

To install BanThis, simply include it in your projects's `composer.json`. 

	"diego-ninja/banthis": "^1",

There are no additional dependencies required for this package to work.

## 🚀 Usage

```php
use Ninja\BanThis\Censor;
use Ninja\BanThis\Dictionary;

$dictionary = Dictionary::withLanguage('en-us');

$censor = new Censor($dictionary);
$string = $censor->clean('A very offensive string with the bad word dick on it');
print_r($string)

Array
(
    [orig] => A very offensive string with the bad word dick on it
    [clean] => A very offensive string with the bad word **** on it
    [matched] => Array
        (
            [0] => dick
        )

)

```

## ⚙️ How it works

In a nutshell, this code takes an array of bad words and compares it to an array of common filter-evasion tactics. It then does a string replacement to insert regex parameters into your badwords array, and then evaluates your input string to that expanded banned word list.

So in your bad words array, you might have:

     [0] => 'ass'

The `preg_replace` functions replace all of the possible shenanigan letters with regex patterns (in lieu of adding the variants onto the end of the array), so the 'ass' in your array gets turned into this, right before the `preg_replace` checks for matches:

     [0] => /(a|a\.|a\-|4|@|Á|á|À|Â|à|Â|â|Ä|ä|Ã|ã|Å|å|α)(s|s\.|s\-|5|\$|§)(s|s\.|s\-|5|\$|§)/i

This means that a word can have none, one or any variety of leet replacements and it will still trip the trigger. Part of the leet filter includes stripping out letter-dash and letter-dots.

This means that the following all evaluate to the "bitch":

- B1tch
- bi7tch
- b.i.t.c.h.
- b-i-t-c-h
- b.1.t.c.h.
- ßitch
- and so on....

## 🔬 Tests
To run the unit tests on this package, simply run `vendor/bin/phpunit` from the package directory.


## 🙏 Credits

This project is developed and maintained by 🥷 [Diego Rin](https://diego.ninja) in his free time.

Special thanks to:

- [snipe](https://github.com/snipe) for developing the [inital code](https://github.com/snipe/banbuilder) that serves BanThis as starting point.
- All the contributors and testers who have helped to improve this project through their contributions.

If you find this project useful, please consider giving it a ⭐ on GitHub!
