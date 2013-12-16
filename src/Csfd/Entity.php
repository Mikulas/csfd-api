<?php

namespace Csfd;


abstract class Entity
{
	
	use UrlAccess;

	private $auth;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder)
	{
		$this->auth = $auth;
		$this->setUrlBuilder($urlBuilder);
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
