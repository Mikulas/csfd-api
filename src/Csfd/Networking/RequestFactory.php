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
		// TODO check if set

		$reflect  = new \ReflectionClass($this->requestClass);
		return $reflect->newInstanceArgs(func_get_args());
	}

}
