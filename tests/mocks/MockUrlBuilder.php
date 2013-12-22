<?php

use Csfd\Networking\UrlBuilder;


class MockUrlBuilder extends UrlBuilder
{

	const URL = 'generated_url';

	public function __construct(array $urls = NULL)
	{
		$this->urls = [];
		$this->map = [];
	}

	public function get(array $path, array $args = NULL)
	{
		return self::URL;
	}

}
