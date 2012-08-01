PHP SPL Class Loader
====================

A simple implementation of the PHP 5.3 SPL Class Loader.

Features
--------

- Implements a PHP 5.3 namespace based autoloader.
- Allows for the inclusion of external search paths.
- Can be configured to throw an exception when classes are not found.
- Plays nicely in multi-autoloader stack environments by default.

Requirements
------------

- PHP 5.3 and above.
- Class files must be named the same as the class name. (ClassName -> ClassName.php, classname -> classname.php)

Usage
-----

```php
<?php
require_once('ClassLoader.php');
$blocking = false;
$loader = new ClassLoader('Example\Src', '/path/to/Example', $blocking);
$loader->register();

// add an external search path
$loader->addPath('/path/to/external/library');
?>
```

Configuration
-------------

- ```setSeperator()```: sets the namespace seperator to use, '\\' by default
- ```setExtension()```: sets the class file name extension, '.php' by default
- ```addPath()```: add a user defined external search path

Blocking Behaviour
------------------

Classloader, by default, will silently fail when a request class cannot be 
found. This allows the next autoloader on the stack to try and resolve the
dependency. 

However, you can set $blocking to true to force ClassLoader to throw a
ClassNotFoundException when it is unable to find the requested class file.
This will prevent other autoloaders on the stack from trying and will allow
you to catch the exception and handle it manually in code.

Legal
-----

Copyright 2012 Matthew Colf <mattcolf@mattcolf.com>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.