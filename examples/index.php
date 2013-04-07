<?php

require_once(dirname(__DIR__).'/vendor/autoload.php');

use Phalcon\Mvc\Micro;
use Peregrine\Peregrine;

$app = new Micro();

$app->notFound(function () use ($app) {
	$app->response->setContent('notfound');
	return $app->response;
});

$app->get('/blog', function() use ($app) {
	$app->response->setStatusCode(200, "OK");
	$app->response->setContent("Blog");
	$app->response->send();
});

$container = new Peregrine($app);
$container->run();
