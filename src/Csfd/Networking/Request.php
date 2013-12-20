<?php

namespace Csfd\Networking;


class Request
{

	const GET = 'GET';
	const POST = 'POST';

	private $content;
	private $headers;
	private $statusCode;

	static $maxRedirects = 10;

	static $_dbgCount = 0;

	public function __construct($url, array $args = NULL, $method = self::GET, $cookie = NULL)
	{
		self::$_dbgCount++;

		$headers = [
			"Content-type: application/x-www-form-urlencoded",
		];
		if ($cookie)
		{
			$headers[] = "Cookie: $cookie";
		}

		$polo = ['http' => [
			'method' => $method,
			'header'=> implode("\r\n", $headers),
			'max_redirects' => self::$maxRedirects,
			'ignore_errors' => TRUE, // will handle it manually
		]];
		if ($args)
		{
			$polo['http']['content'] = http_build_query($args);
		}

		$this->content = file_get_contents($url, NULL, stream_context_create($polo));

		$rawHeaders = $http_response_header;
		$this->statusCode = (int) substr($rawHeaders[0], strlen('HTTP/1.1 '));
		array_shift($rawHeaders);
		$headers = [];
		foreach ($rawHeaders as $header)
		{
			$p = strpos($header, ':');
			if ($p === FALSE) {
				break; // headers from next request 
			}
			$key = strToLower(substr($header, 0, $p));
			$value = trim(substr($header, $p + 1));

			$headers[$key][] = $value;
		}
		$this->headers = $headers;
	}

	/** @TODO remove, not used */
	public static function withoutRedirect($url, array $args = NULL, $method = self::GET, $cookie = NULL)
	{
		$tmp = self::$maxRedirects;
		self::$maxRedirects = 0;
		$res = new self($url, $args, $method, $cookie);
		self::$maxRedirects = $tmp;
		return $res;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	public function getCookie()
	{
		$cookies = [];
		foreach ($this->headers['set-cookie'] as $line)
		{
			$cookies[] = explode(';', $line)[0]; // ignore path and flags
		}
		return implode('; ', $cookies);
	}

	/**
	 * @return int status code
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

}
