<?php

namespace Csfd\Entities;

use Csfd\Authentication\Authenticator;
use Csfd\Networking\Request;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlAccess;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers\Parser;


abstract class Entity
{
	
	use UrlAccess;
	use CachingGetter;

	private $auth;
	private $parser;
	private $requestFactory;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder, Parser $parser, RequestFactory $requestFactory)
	{
		$this->auth = $auth;
		$this->setUrlBuilder($urlBuilder);
		$this->parser = $parser;
		$this->requestFactory = $requestFactory;
	}

	protected function getParser()
	{
		return $this->parser;
	}

	public function request($url, array $args = NULL, $method = Request::GET)
	{
		return call_user_func_array([$this->requestFactory, 'create'], func_get_args());
	}

	/**
	 * Make authenticated request. Might trigger login if
	 * cookie is not stored yet.
	 * 
	 * @param string $url
	 * @param array $args
	 * @param string $method Request::GET|POST
	 * @return Request
	 */
	public function authRequest($url, array $args = NULL, $method = Request::GET)
	{
		return $this->request($url, $args, $method, $this->auth->getCookie());
	}

}
