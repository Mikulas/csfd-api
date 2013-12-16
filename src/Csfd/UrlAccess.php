<?php

namespace Csfd;


trait UrlAccess
{

	private $urlBuilder;

	public function setUrlBuilder(UrlBuilder $urlBuilder)
	{
		$this->urlBuilder = $urlBuilder;
	}

	/**
	 * Gets url from config. Prepends root url.
	 * Traverses config by class namespace under Csfd.
	 * @param string $key
	 * @return string url
	 */
	protected function getUrl($key)
	{
		if (!$this->urlBuilder)
		{
			throw new \Exception('urlBuilder not set'); // TODO
		}

		$path = explode('\\', strToLower(get_class($this)));
		$path = array_splice($path, 1); // remove first (Csfd)
		array_push($path, $key);
		return $this->urlBuilder->get($path);
	}
	
}
