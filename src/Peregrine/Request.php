<?php

namespace Peregrine;

use \Phalcon\HTTP\Request as PhalconRequest;
use \Mongrel2\Request as M2Request;

class Request extends PhalconRequest
{
	public function __construct(M2Request $request){
		$this->request = $request;
	}
}
