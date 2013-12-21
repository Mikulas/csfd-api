<?php

namespace Csfd\Networking;

use Csfd\InternalException;


class RequestFactory
{

	private $requestClass;

	private $cache;

	public function setRequestClass($class)
	{
		$this->requestClass = $class;
	}

	public function create($url, array $args = NULL, $method = Request::GET)
	{
		if (!$this->requestClass)
		{
			throw new InternalException('Request class is not set. Hint: call setRequestClass.');
		}

		$hash = $this->hash($url, $args, $method);
		if (!isset($this->cache[$hash]))
		{
			$reflect  = new \ReflectionClass($this->requestClass);
			$res = $reflect->newInstanceArgs(func_get_args());
			$this->cache[$hash] = $res;
		}
		return $this->cache[$hash];
	}

	private function hash($url, array $args = NULL, $method = Request::GET)
	{
		return md5(json_encode([$url, $args, $method]));
	}

}
