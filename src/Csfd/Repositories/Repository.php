<?php

namespace Csfd\Repositories;

use Csfd\Authenticator;
use Csfd\UrlBuilder;
use Csfd\Networking\RequestFactory;


abstract class Repository
{

	protected $authenticator;
	protected $urlBuilder;
	protected $requestFactory;

	protected $entityClass;
	protected $parserClass;

	/** singleton of $parserClass */
	private $parser;

	public function __construct(Authenticator $authenticator, UrlBuilder $urlBuilder, RequestFactory $requestFactory)
	{
		$this->authenticator = $authenticator;
		$this->urlBuilder = $urlBuilder;
		$this->requestFactory = $requestFactory;
	}

	public function setEntityClass($entityClass)
	{
		$this->entityClass = $entityClass;
	}

	public function setParserClass($parserClass)
	{
		$this->parserClass = $parserClass;
	}

	public function get($id)
	{
		$class = $this->$entityClass;
		return new $class($this->authenticator, $this->urlBuilder, $this->getParser(), $this->requestFactory, $id);
	}

	public function getParser()
	{
		if (!$this->parser)
		{
			$class = $this->parserClass;
			$this->parser = new $class;
		}
		return $this->parser;
	}

}
