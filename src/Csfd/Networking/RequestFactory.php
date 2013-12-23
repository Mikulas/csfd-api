<?php

namespace Csfd\Networking;

use Csfd\InternalException;


class RequestFactory
{

	private $requestClass;

	protected $cache;

	public function setRequestClass($class)
	{
		$this->requestClass = $class;
	}

	protected function getCached($hash)
	{
		if (isset($this->cache[$hash]))
		{
			return $this->cache[$hash];
		}
		return NULL;
	}

	protected function saveCache($hash, $result)
	{
		$this->cache[$hash] = $result;
	}

	public function create($url, array $args = NULL, $method = Request::GET, $cookie = NULL)
	{
		if (!$this->requestClass)
		{
			throw new InternalException('Request class is not set. Hint: call setRequestClass.');
		}

		$hash = $this->hash($url, $args, $method);
		if ($res = $this->getCached($hash))
		{
			return $res;
		}

		$reflect  = new \ReflectionClass($this->requestClass);
		$res = $reflect->newInstanceArgs(func_get_args());
		$this->saveCache($hash, $res);
		return $res;
	}

	protected function hash($url, array $args = NULL, $method = Request::GET)
	{
		return md5(json_encode([$url, $args, $method]));
	}

}
