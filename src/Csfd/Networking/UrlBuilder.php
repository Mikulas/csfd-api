<?php

namespace Csfd\Networking;

use Collections\HashMap;
use Collections\KeyException;
use Csfd\InternalException;
use Symfony\Component\Yaml\Yaml;


class UrlBuilder
{

	/** @var array urls.yml */
	private $urls;

	/** @var HashMap */
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
		$this->map = new HashMap;
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

		return $this->urls['root'] . $this->replacePlaceholders($node, $args);
	}

	/**
	 * @return \Collections\HashMap
	 */
	public function getMap()
	{
		return $this->map;
	}

	/**
	 * Replace placeholders with real data
	 * @param string $url
	 * @param array $moreMappings
	 * @return string url with replaced placeholders
	 */
	protected function replacePlaceholders($url, array $moreMappings = NULL)
	{
		$map = $this->map->mergeWith($moreMappings ?: []);
		$url = preg_replace_callback('~{\$(?P<name>\w+)}~', function($m) use ($map) {
			try {
				return $map->get($m['name']);
			} catch (KeyException $e) {
				throw new InternalException("Failede to resolve placeholder $m[name].", NULL, $e);
			}
		}, $url);

		return $url;
	}

}
