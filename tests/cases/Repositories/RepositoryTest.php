<?php

namespace Csfd\Repositories;

use TestCase;


class RepositoryTest extends TestCase
{

	private $repo;

	/** @covers Csfd\Repositories\Repository::__construct */
	public function setUp()
	{
		$this->repo = new TestRepository(
			$this->getMockAuthenticator(),
			$this->getMockUrlBuilder(),
			$this->getMockRequestFactory()
		);
	}

	/**
	 * @covers Csfd\Repositories\Repository::get()
	 * @expectedException Csfd\InternalException
	 */
	public function testGetEntityNotSet()
	{
		$this->repo->get(1);
	}

	/**
	 * @covers Csfd\Repositories\Repository::setEntityClass()
	 * @covers Csfd\Repositories\Repository::get()
	 */
	public function testGet()
	{
		$id = 1;
		$class = 'Csfd\Entities\User';

		$this->repo->setParserClass('Csfd\Parsers\User');

		$this->repo->setEntityClass($class);
		$ret = $this->repo->get($id);
		$this->assertInstanceOf($class, $ret);
		$this->assertSame($id, $ret->getId());
	}

	/**
	 * @covers Csfd\Repositories\Repository::getParser()
	 * @expectedException Csfd\InternalException
	 */
	public function testGetParserNotSet()
	{
		$this->repo->getParser();
	}

	/**
	 * @covers Csfd\Repositories\Repository::setParserClass()
	 * @covers Csfd\Repositories\Repository::getParser()
	 */
	public function testGetParser()
	{
		$class = 'Csfd\Parsers\User';

		$this->repo->setParserClass($class);
		$this->assertInstanceOf($class, $this->repo->getParser());
	}

}

class TestRepository extends Repository
{

}
