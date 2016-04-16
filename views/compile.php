<?php

function getCurrentUri(){
	$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
	$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
	if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
	$uri = '/' . trim($uri, '/');
	return explode("/" , trim($uri, "/"));
}

include("header.php");
include("showHome.php");
include("showLogin.php");
include("footer.php");

 ?>
