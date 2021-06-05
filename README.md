A simle file system classes
=======================

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stk2k/file-tools.svg?style=flat-square)](https://packagist.org/packages/stk2k/file-tools)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/stk2k/file-tools.svg?branch=master)](https://travis-ci.org/stk2k/file-tools)
[![Coverage Status](https://coveralls.io/repos/github/stk2k/file-tools/badge.svg?branch=master)](https://coveralls.io/github/stk2k/file-tools?branch=master)
[![Code Climate](https://codeclimate.com/github/stk2k/file-tools/badges/gpa.svg)](https://codeclimate.com/github/stk2k/file-tools)
[![Total Downloads](https://img.shields.io/packagist/dt/stk2k/file-tools.svg?style=flat-square)](https://packagist.org/packages/stk2k/file-tools)

## Description

A simle file system classes


## Feature

- Simple file class(File)
- File function facade class(FileFacade)

## Usage

### making a file

```php
use FileTools\FileSystem;

FileSystem::put('/path/to/file', 'Hello, World');
```

### deleting a file

```php
use FileTools\FileSystem;

FileSystem::delete('/path/to/file');
```

### getting file's content

```php
use FileTools\FileSystem;

// getting whole content as string
$ret = FileSystem::get('/path/to/file');
echo $ret;

// getting whole content as array
$ret = FileSystem::getAsArray('/path/to/file');
print_r($ret);
```

### putting file's content

```php
use FileTools\File;
use FileTools\FileSystem;

// putting string content
$ret = FileSystem::put('/path/to/file', 'Hello, World!');
echo $ret->get();       // Hello, World!

// putting array(of strings) content
$ret = FileSystem::put('/path/to/file', ['Foo', 'Bar']);
echo $ret->get();
// Foo
// Bar

// putting File object
file_put_contents('/path/to/file1', 'Hello, World!');
$ret = FileSystem::put('/path/to/file2', new File('/path/to/file1'));
echo $ret->get();       // Hello, World!

// putting object content(Stringable)
class MyStringableObject
{
    public function __toString() : string
    {
        return 'Hello, World!';
    }
}
$ret = FileSystem::put('/path/to/file', new MyStringableObject());
echo $ret->get();       // Hello, World!

```

### file object

```php
use FileTools\File;

$file = new File('/path/to/file');
echo $ret->get();
```



## Requirement

PHP 7.2 or later

## Installing stk2k/file-tools

The recommended way to install stk2k/file-tools is through
[Composer](http://getcomposer.org).

```bash
composer require stk2k/file-tools
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## License
This library is licensed under the MIT license.

## Author

[stk2k](https://github.com/stk2k)

## Disclaimer

This software is no warranty.

We are not responsible for any results caused by the use of this software.

Please use the responsibility of the your self.


