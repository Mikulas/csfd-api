<?php

namespace Csfd\Repositories;

use Csfd\Authentication\Authenticator;
use Csfd\InternalException;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlBuilder;


abstract class Repository
{

	protected $authenticator;
	protected $urlBuilder;
	protected $requestFactory;

	/** @var string class name */
	protected $entityClass;

	/** @var string class name */
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
		if (!$this->entityClass)
		{
			throw new InternalException('Entity class is not set. Hint: call setEntityClass.');
		}

		$class = $this->entityClass;
		return new $class($this->authenticator, $this->urlBuilder, $this->getParser(), $this->requestFactory, $id);
	}

	public function getParser()
	{
		if (!$this->parserClass)
		{
			throw new InternalException('Parser class is not set. Hint: call setParserClass.');
		}

		if (!$this->parser)
		{
			$class = $this->parserClass;
			$this->parser = new $class;
		}
		return $this->parser;
	}

}
