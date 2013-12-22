<?php

namespace Csfd\Networking;

use Csfd\InternalException;
use Symfony\Component\Yaml\Yaml;


class UrlBuilder
{

	/** @var array urls.yml */
	private $urls;

	/** @var array [key => value] */
	private $map;

	public static function factory($configFile)
	{
		if (!is_file($configFile))
		{
			throw new InternalException("Config file `$configFile` does not exist.");
		}

		$urls = Yaml::parse(file_get_contents($configFile));
		if (!self::validate($urls))
		{
			throw new InternalException("Config file `$configFile` is not valid.");
		}
		return new static($urls);
	}

	/**
	 * @param array $definition
	 * @return bool
	 */
	private static function validate(array $definition = NULL)
	{
		if (!$definition || !isset($definition['root']) || !is_string($definition['root']))
		{
			return FALSE;
		}
		return TRUE;
	}

	public function __construct(array $urls)
	{
		$this->urls = $urls;
		$this->map = [];
	}

	/**
	 * @return string root url with scheme, ending with slash
	 */
	public function getRoot()
	{
		return $this->urls['root'];
	}

	public function get(array $path, array $args = NULL)
	{
		$node = $this->urls;
		$stack = $path; // preserve original for error message
		while ($key = array_shift($stack))
		{
			if (!isset($node[$key]))
			{
				throw new InternalException("Path '" . implode(':', $path) . "' not found in config, error at '$key'.");
			}
			$node = $node[$key];
		}

		return $this->urls['root'] . $this->map($node, $args);
	}

	/**
	 * Variables in format {$key} are replaced
	 * @param scalar $key
	 * @param scalar $value
	 */
	public function addMap($key, $value)
	{
		$this->map[$key] = $value;
	}

	/**
	 * Replace placeholders with real data
	 * @param string $url
	 * @return string url with replaced placeholders
	 */
	protected function map($url, array $moreMappings = NULL)
	{
		$map = array_merge($this->map, (array) $moreMappings);
		$url = preg_replace_callback('~{\$(?P<name>\w+)}~', function($m) use ($map) {
			$name = $m['name'];
			if (!isset($map[$name]))
			{
				throw new InternalException("Placeholder {\$$name} not mapped."); // TODO
			}
			return $map[$name];
		}, $url);

		return $url;
	}

}
