<?php

namespace Csfd\Networking;


class RequestFactory
{

	private $requestClass;

	public function setRequestClass($class)
	{
		$this->requestClass = $class;
	}

	public function create()
	{
		$reflect  = new \ReflectionClass($this->requestClass);
		return $reflect->newInstanceArgs(func_get_args());
	}

}
