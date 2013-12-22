<?php

use Csfd\Networking\RequestFactory;


class CachedRequestFactory extends RequestFactory
{

	static $tempDir;

	private function getFile($hash)
	{
		return self::$tempDir . "/$hash";
	}

	protected function getCached($hash)
	{
		// TODO should probably not cache POST requests
		if (isset($this->cache[$hash]))
		{
			return $this->cache[$hash];
		}

		$file = $this->getFile($hash);
		if (is_file($file))
		{
			$res = unserialize(file_get_contents($file));
			$this->cache[$hash] = $res;
			return $res;
		}

		return NULL;
	}

	protected function saveCache($hash, $result)
	{
		$this->cache[$hash] = $result;
		file_put_contents($this->getFile($hash), serialize($result));
	}

}
