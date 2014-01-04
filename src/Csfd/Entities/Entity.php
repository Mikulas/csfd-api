<?php

namespace Csfd\Entities;

use Csfd\Authentication\Authenticator;
use Csfd\InternalException;
use Csfd\Networking\Request;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlAccess;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers\Parser;
use Csfd\Repositories\Repository;


abstract class Entity
{

	use CachingGetter;
	use UrlAccess;

	private $auth;
	private $parser;
	private $requestFactory;
	private $repository;

	protected $id;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder, Parser $parser, RequestFactory $requestFactory, $id)
	{
		$this->auth = $auth;
		$this->setUrlBuilder($urlBuilder);
		$this->parser = $parser;
		$this->requestFactory = $requestFactory;
		$this->id = $id;
	}

	public function setRepository(Repository $repo)
	{
		$this->repository = $repo;
	}

	/**
	 * @param string $name repo name or nothing to return current entity repo
	 * @return Repository
	 */
	protected function getRepository($name = NULL)
	{
		if (!$this->repository)
		{
			throw new InternalException('Entity is not attached to repository');
		}
		if ($name)
		{
			return $this->repository->getRepository($name);
		}
		return $this->repository;
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

	/**
	 * Extends capabilities of CachingGetter
	 * Does not need definition of _getFoo() method if it would
	 * only call _getFoo() on parser. Define _getFoo() to override.
	 *
	 * @param string $property
	 * @param array|NULL $args
	 * @return mixed
	 */
	protected function _get($property, $args = NULL)
	{
		$args = func_get_args();
		array_shift($args);

		$html = $this->request($this->getUrl($this->getUrlKey($property), ['entityId' => $this->id]))->getContent();
		array_unshift($args, $html);

		$method = 'get' . ucFirst($property);
		return call_user_func_array([$this->getParser(), $method], $args);
	}

	protected function getUrlKey($property)
	{
		return 'default';
	}

	/**
	 * @return int id
	 */
	public function getId()
	{
		return $this->id;
	}

}
