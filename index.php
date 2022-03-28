<?php
require_once('config.php');
require_once ('vendor/autoload.php');

$app = new Slim\CSlim(array(
    'debug' => true,
    'mode' => 'development',
    'templates.path' => 'views',
	'defaultLayout' => 'layout.php',
	'defaultTitle' => 'Zufallsgenerator',
));


$app->get('/', function(){
	$c = new Slim\Controller;
	
	$c->renderPartial('layout.php', array(
		'title' => 'Zufallsgenerator',
		'content' => '',
	));
});
$app->run();
