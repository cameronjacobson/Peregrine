<?php

namespace Peregrine;

use Request;
use Response;
use \Mongrel2\Connection;
use \Phalcon\Mvc\Micro;
use \Phalcon\DI\FactoryDefault;

class Peregrine
{
	public function __construct(Micro $app, FactoryDefault $di){
		$this->sender_id = self::getId();
		$this->connection = new Connection($this->sender_id, "tcp://127.0.0.1:9997", "tcp://127.0.0.1:9996");

		$this->app = $app;
		$this->registerRoutes();
	}

	public function registerRoutes(){
		foreach($this->app->router->getRoutes() as $route){
			$pattern = $route->getPattern();
			// REGISTER ROUTE HERE
		}
	}

	public static function getId() {
		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}

	private function tearDown(){
		gc_collect_cycles();
	}

	public function run(){
		while(true){
			$req = $this->connection->recv();

			if($req->is_disconnect()){
				continue;
			}

			$this->di = $di ?: new FactoryDefault();

			$this->di->set('request', function () use ($req) {
				$request = new Request($req);
				return $request;
			});

			$this->di->set('response', function () use ($req, $conn) {
				$response = new Response($req, $conn);
				return $response;
			});

			$this->app->setDI($this->di);

			$this->app->handle();
		}
	}
}
