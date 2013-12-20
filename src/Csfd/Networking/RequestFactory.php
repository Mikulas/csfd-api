<?php

namespace Csfd\Networking;

use Csfd\InternalException;


class RequestFactory
{

	private $requestClass;

	public function setRequestClass($class)
	{
		$this->requestClass = $class;
	}

	public function create()
	{
		if (!$this->requestClass)
		{
			throw new InternalException('Request class is not set. Hint: call setRequestClass.');
		}

		$reflect  = new \ReflectionClass($this->requestClass);
		return $reflect->newInstanceArgs(func_get_args());
	}

}
