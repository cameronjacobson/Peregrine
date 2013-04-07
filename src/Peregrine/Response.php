<?php

namespace Peregrine;

use \Phalcon\HTTP\Response as PhalconResponse;
use \Mongrel2\Request;
use \Mongrel2\Connection;

class Response extends PhalconResponse
{
	public function __construct(Request $request, Connection $conn){
		$this->request = $request;
		$this->connection = $conn;
		parent::__construct();
	}

	public function send(){
		$this->conn->reply_http($this->req, $this->getContent());
	}
}
