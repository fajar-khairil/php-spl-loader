<?php

/**
*	An SplClassLoader implementation for PHP 5.3.
*	
*	Example usage for loading classes from the Example/Src namespace:
*		$loader = new SplLoader('Example\Src','/path/to/Example')
*		$loader->register();
*
*	You can also load classes from outside the current namespace.
*		$loader->addPath('/path/to/external/library_one');
*		$loader->addPath('/path/to/external/library_two');
*
*	If no matches are found, the loader will search the PHP include path.
*
*	The blocking parameter sets whether this class plays nicely in a multi
*	autoloader stack environment or not. Setting to true will throw a
*	ClassNotFoundException when no matching class can be found. Setting to 
*	false will cause the loader to silently fail and allow the next autoloader
*	on the stack to resolve the depedency.
*/

/**
*	Copyright 2012 Matthew Colf <mattcolf@mattcolf.com>
*
*	Licensed under the Apache License, Version 2.0 (the "License");
*	you may not use this file except in compliance with the License.
*	You may obtain a copy of the License at
*
*	http://www.apache.org/licenses/LICENSE-2.0
*
*	Unless required by applicable law or agreed to in writing, software
*	distributed under the License is distributed on an "AS IS" BASIS,
*	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*	See the License for the specific language governing permissions and
*	limitations under the License.
*/

class ClassLoader
{
	private $namespace;
	private $path;
	private $systemPath;
	private $userPath = array();
	private $extension = '.php';
	private $seperator = '\\';
	private $blocking;

	/**
	*	Create a new SplLoader that will load classes in the
	*	specified namespace.
	*
	*	@param string $ns The namespace to load from.
	*	@param string $path The base path to load from.
	*	@param bool $blocking True to throw an exception when a matching
	*						  class cannot be found. False to silently fail
	*						  and allow the next autoloader to try.
	*	@return void
	*/
	public function __construct($ns = null, $path = null, $blocking = false)
	{
		$this->namespace = $ns;
		$this->path = $path;
		$this->systemPath = explode(PATH_SEPARATOR, get_include_path());
		$this->blocking = $blocking;
	}

	/**
	*	Set the namespace seperator used by classes in the namespace
	*	of this loader.
	*
	*	@param string $seperator The seperator character string.
	*	@return void
	*/
	public function setSeperator($seperator)
	{
		$this->seperator = $seperator;
	}

	/**
	*	Get the namespace seperator being used by this loader.
	*
	*	@return string 
	*/
	public function getSeperator()
	{
		return $this->seperator;
	}

	/**
	*	Set the file extension of the class files to load.
	*
	*	@param string $extension The file extension including dot.
	*	@return void
	*/
	public function setExtension($extension)
	{
		$this->extension = $extension;
	}

	/**
	*	Gets the current file extension being used to load classes.
	*
	*	@return string
	*/
	public function getExtension()
	{
		return $this->extension;
	}

	/**
	*	Add additional search paths to use when loading classes
	*	from outside the current namespace.
	*
	*	@param string $path The search path to load from.
	*	@return void
	*/
	public function addPath($path)
	{
		$this->userPath[] = $path;
	}
		
	/**
	*	Register this loader onto the SPL autoloader stack.
	*
	*	@return void
	*/
	public function register()
	{
		spl_autoload_register(array($this,'load'));
	}

	/**
	*	Unregister this loader from the SPL autoloader stack.
	*
	*	@return void
	*/
	public function unregister()
	{
		spl_autoload_unregister(array($this,'load'));
	}

	/**
	*	Loads the requested class or interface.
	*
	*	@param string $class The name of the class to load.
	*	@return void
	*/
	public function load($class)
	{
		$attempted = array();

		// namespace
		$ns = $this->namespace.$this->seperator;
		if ($this->namespace == null || $ns === substr($class,0,strlen($ns)))
		{
			$file = '';

			if (($lastsep = strripos($class, $this->seperator)) !== false)
			{
				$namespace = substr($class,0,$lastsep);
				$class = substr($class,$lastsep+1);
				$file = str_replace($this->seperator, DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
			}

			$file .= str_replace('_', DIRECTORY_SEPARATOR, $class).$this->extension;

			if ($this->path != null) $file = $this->path.DIRECTORY_SEPARATOR.$file;

			if (file_exists($file))
			{
				require($file);
				return;
			}
			else $attempted[] = $file;
		}		

		// additional search paths
		foreach (array_merge($this->userPath,$this->systemPath) as $path)
		{
			$file = $path.DIRECTORY_SEPARATOR.$class.$this->extension;
			if (file_exists($file))
			{
				require($file);
				return;
			}
			else $attempted[] = $file;
		}

		// no matches
		if ($this->blocking) 
		{
			throw new ClassNotFoundException("Unable to load $class.", $class, $attempted);
		}
	}

}

/**
*	A simple exception to throw when when a class cannot be found.
*/

class ClassNotFoundException extends Exception
{
	protected $class;
	protected $attempted;

	/**
	*	Create a new ClassNotFoundException.
	*
	*	@param string $message The exception text.
	*	@param string $class The class name.
	*	@param array $attempted An array of all paths attempted.
	*/

	public function __construct($message, $class, $attempted)
	{
		$this->class = $class;
		$this->attempted = $attempted;
		parent::__construct($message);
	}

	/**
	*	Get the class name that failed to load.
	*
	*	@return string The classname.
	*/

	public function className()
	{
		return $this->class;
	}

	/**
	*	Get an array of all paths attempted when trying to load the class.
	*
	*	@return array The attempted paths.
	*/

	public function attempted()
	{
		return $this->attempted;
	}

}
