<?php
require_once('lib/idiorm.php');
try

{
    ORM::configure('mysql:host=' . $dbhost . ';dbname=' . $dbname);
    ORM::configure('username', $dbuser);
    ORM::configure('password', $dbpass);
}
catch(Exception $e)
{
	echo "Fehler bei der Config";
	exit;
}
require_once('lib/Slim/Slim.php');
\Slim\Slim::registerAutoloader();
