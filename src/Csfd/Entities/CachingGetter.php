<?php

namespace Csfd\Entities;

use Csfd\InternalException;


// TODO just move it to Entity
trait CachingGetter
{

	private $cache;

	/**
	 * Saves property to cache. Used internaly to optimize.
	 * For example when id and name is known from search,
	 * getting name does not need to create a new http request.
	 *
	 * @param mixed $property
	 * @param mixed $value
	 */
	public function setPropertyCache($property, $value)
	{
		$this->cache[$property] = $value;
	}

	private function getPropertyFromGetter($method)
	{
		return lcFirst(substr($method, strlen('get')));
	}

	/**
	 * Invokes _getProperty() for getProperty().
	 * Caches return values.
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function __call($method, array $args)
	{
		$property = $this->getPropertyFromGetter($method);
		if (isset($this->cache[$property]))
		{
			return $this->cache[$property];
		}

		if (strpos($method, 'get') === 0)
		{
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
				$this->cache[$property] = $res;
				return $res;
			}
			else if (method_exists($this, '_get'))
			{
				array_unshift($args, $property);
				$res = call_user_func_array([$this, '_get'], $args);
				$this->cache[$property] = $res;
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
