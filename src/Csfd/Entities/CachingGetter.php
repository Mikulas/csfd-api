<?php

namespace Csfd\Entities;

use Csfd\InternalException;


// TODO just move it to Entity
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
			$property = lcFirst(substr($method, strlen('get')));
			$getter = "_$method";
			if (method_exists($this, $getter))
			{
				$query = [];
				if ($this instanceof Entity)
				{
					$query['entityId'] = $this->id;	
					$html = $this->request($this->getUrl($this->getUrlKey($property), $query))->getContent();
					array_unshift($args, $html);
				}
				$res = call_user_func_array([$this, $getter], $args);
				$this->cache[$method] = $res;
				return $res;
			}
			else if (method_exists($this, '_get'))
			{
				array_unshift($args, $property);
				$res = call_user_func_array([$this, '_get'], $args);
				$this->cache[$method] = $res;
				return $res;
			}
		}

		$class = get_class($this);
		throw new InternalException("Call to undefined method $class::$method.");
	}

	protected function getUrlKey()
	{
		return 'default';
	}

}
