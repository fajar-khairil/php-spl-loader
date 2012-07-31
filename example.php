<?php

require_once('ClassLoader.php');
$blocking = true;
$loader = new ClassLoader(__NAMESPACE__,__DIR__,$blocking);
$loader->register();

// Add a custom user path, relative is ok.
// System paths are included by default, but are searched last after all
// namespace and user provided paths.
$loader->addPath('../oauth');

try {

	//$consumer = new OAuthConsumer();

	// should fail
	$jim = new Jim();

} 
// when blocking is true, you can catch failed loads
catch(ClassNotFoundException $ex)
{
	echo "Unable to load a class: <br />";
	echo "Class Name: ".$ex->className()."<br />";
	echo "Attempted Paths: "; var_dump($ex->attempted()); echo "<br />";
}

echo "DONE";