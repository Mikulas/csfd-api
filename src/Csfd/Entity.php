<?php

namespace Csfd;


abstract class Entity
{
	
	use UrlAccess;

	private $auth;
	private $parser;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder, Parsers\Parser $parser)
	{
		$this->auth = $auth;
		$this->setUrlBuilder($urlBuilder);
		$this->parser = $parser;
	}

	protected function getParser()
	{
		return $this->parser;
	}

	/**
	 * Make authenticated request. Might trigger login if
	 * cookie is not stored yet.
	 * @param string $url
	 * @param array $args
	 * @param string $method Request::GET|POST
	 * @return Request
	 */
	public function authRequest($url, array $args = NULL, $method = Request::GET)
	{
		return new Request($url, $args, $method, $this->auth->getCookie());
	}

}
