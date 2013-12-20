<?php

namespace Csfd\Networking;

use Symfony\Component\Yaml\Yaml;


class UrlBuilder
{

	private $urls;
	private $map;

	public static function factory($configFile)
	{
		var_dump($configFile);
		$urls = Yaml::parse(file_get_contents($configFile));
		return new static($urls);
	}

	public function __construct(array $urls)
	{
		// TODO validate config
		//  - probably when passed to Csfd
		$this->urls = $urls;
		$this->map = [];
	}

	public function getRoot()
	{
		return $this->urls['root'];
	}

	public function get(array $path)
	{
		$node = $this->urls;
		$stack = $path; // preserve original for error message
		while ($key = array_shift($stack))
		{
			if (!isset($node[$key]))
			{
				throw new \Exception("Path '" . implode(':', $path) . "' not found in config, error at '$key'."); // @TODO
			}
			$node = $node[$key];
		}

		return $this->urls['root'] . $this->map($node);
	}

	public function addMap($key, $value)
	{
		$this->map[$key] = $value;
	}

	/**
	 * Replace placeholders with real data
	 * @param string $url
	 * @return string url with replaced placeholders
	 */
	public function map($url)
	{
		$map = $this->map;
		var_dump($map);
		$url = preg_replace_callback('~{\$(?P<name>\w+)}~', function($m) use ($map) {
			$name = $m['name'];
			if (!isset($map[$name]))
			{
				throw new \Exception("Placeholder {\$$name} not mapped."); // TODO
			}
			return $map[$name];
		}, $url);

		return $url;
	}

}
