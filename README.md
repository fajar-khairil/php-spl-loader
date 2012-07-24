PHP SPL Class Loader
====================

A simple implementation of the PHP 5.3 SPL Class Loader.

This is a work in progress and has not been fully tested. Use at your own risk.

Features
--------

- Implements a PHP 5.3 namespace based autoloader.
- Allows for the inclusion of external search paths.

Requirements
------------

- PHP 5.3 and above.

Usage
-----

```
require_once('ClassLoader.php');
$loader = new ClassLoader('Example\Src','/path/to/Example');
$loader->register();

// add an external search path
$loader->addPath('/path/to/external/library');
```

Configuration
-------------

- ```setSeperator()```: sets the namespace seperator to use, '\\' by default
- ```setExtension()```: sets the class file name extension, '.php' by default
- ```addPath()```: add a user defined external search path

Legal
-----

Copyright (c) 2012, Matt Colf

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted, provided that the above
copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.