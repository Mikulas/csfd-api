<?php

namespace Csfd\Entities;

use Csfd\InternalException;


trait CachingGetter
{

	private $cache;

	/**
	 * Invokes _getProperty() for getProperty().
	 * Caches return values.
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function __call($method, array $args)
	{
		if (isset($this->cache[$method]))
		{
			return $this->cache[$method];
		}

		if (strpos($method, 'get') === 0)
		{
			$getter = "_$method";
			if (method_exists($this, $getter))
			{
				// TODO inject args
				$res = $this->$getter();
				$this->cache[$method] = $res;
				return $res;
			}
		}

		$class = get_class($this);
		throw new InternalException("Call to undefined method $class::$method.");
	}

}
