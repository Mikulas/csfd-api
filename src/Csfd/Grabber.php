<?php

namespace Csfd;

use Sunra\PhpSimple\HtmlDomParser;


class Grabber
{

	const GET = 'GET';
	const POST = 'POST';


	/** @var string */
	protected $url;



	public function __construct($url = NULL)
	{
		$this->url = $url ? $url : 'http://www.csfd.cz/';
	}



	public function request($http, $method, $args = [])
	{
		$query = http_build_query($args);

		$context = stream_context_create([
			'http' => [
				'method'  => $http,
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $http === self::POST ? $query : NULL,
			]
		]);

		$url = $this->url . $method . ($http === self::GET ? '?' . $query : '');
		$result = file_get_contents($url, FALSE, $context);

		if (!$result) {
			throw new \RuntimeException('Invalid response received.');
		}

		return HtmlDomParser::str_get_html($result);
	}

}
