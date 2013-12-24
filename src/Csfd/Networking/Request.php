<?php

namespace Csfd\Networking;


class Request
{

	const GET = 'GET';
	const POST = 'POST';

	private $content;
	private $headers;
	private $statusCode;

	public static $_dbgCount = 0;

	public function __construct($url, array $args = NULL, $method = self::GET, $cookie = NULL)
	{
		self::$_dbgCount++;

		$headers = [
			'Content-type: application/x-www-form-urlencoded',
		];
		if ($cookie)
		{
			$headers[] = "Cookie: $cookie";
		}

		$polo = ['http' => [
			'method' => $method,
			'header'=> implode("\r\n", $headers),
			'ignore_errors' => TRUE, // will handle it manually
		]];
		if ($args)
		{
			$polo['http']['content'] = http_build_query($args);
		}

		$this->content = @file_get_contents($url, NULL, stream_context_create($polo));
		if ($this->content === FALSE)
		{
			if (@file_get_contents('http://google.com') === FALSE)
			{
				throw new Exception('Request failed. Your internet connection is down.', Exception::NO_CONNECTION);
			}
			else
			{
				throw new Exception('Request failed. You have been blacklisted by CSFD firewall, which usually lasts for about a day.', Exception::BLOCKED);
			}
		}

		// @codingStandardsIgnoreStart
		$rawHeaders = $http_response_header;
		// @codingStandardsIgnoreEnd

		$this->statusCode = (int) substr($rawHeaders[0], strlen('HTTP/1.1 '));
		array_shift($rawHeaders);
		$headers = [];
		foreach ($rawHeaders as $header)
		{
			$p = strpos($header, ':');
			if ($p === FALSE)
			{
				// ignore status header from redirected request
				continue;
			}
			$key = strToLower(substr($header, 0, $p));
			$value = trim(substr($header, $p + 1));

			$headers[$key][] = $value;
		}
		$this->headers = $headers;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return string http cookie format
	 */
	public function getCookie()
	{
		if (!isset($this->headers['set-cookie']))
		{
			return '';
		}

		$cookies = [];
		foreach ($this->headers['set-cookie'] as $line)
		{
			$cookies[] = explode(';', $line)[0]; // ignore path and flags
		}
		return implode('; ', $cookies);
	}

	/**
	 * @return NULL|url if redirected
	 */
	public function getRedirectUrl()
	{
		if (!isset($this->headers['location']))
		{
			return NULL;
		}
		return end($this->headers['location']);
	}

	/**
	 * @return int status code
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

}
