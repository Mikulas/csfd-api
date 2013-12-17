<?php

namespace Csfd\Repositories;

use Csfd\Authenticator;
use Csfd\UrlBuilder;


class Repository
{

	protected $authenticator;
	protected $urlBuilder;
	protected $entityClass;

	public function __construct(Authenticator $authenticator, UrlBuilder $urlBuilder)
	{
		$this->authenticator = $authenticator;
		$this->urlBuilder = $urlBuilder;
	}

	public function setEntityClass($entityClass)
	{
		$this->entityClass = $entityClass;
	}

	public function get($id)
	{
		$class = $this->$entityClass;
		return new $class($this->authenticator, $this->urlBuilder, $id);
	}

}
