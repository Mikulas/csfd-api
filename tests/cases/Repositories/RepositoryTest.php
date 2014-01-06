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
	 * @covers Csfd\Repositories\Repository::setContainer
	 */
	public function testSetContainer()
	{
		$e = Access($this->repo);
		$container = $this->getMockContainer();
		$e->setContainer($container);
		$this->assertSame($e->container, $container);
	}

	/**
	 * @covers Csfd\Repositories\Repository::getRepository
	 * @expectedException Csfd\InternalException
	 */
	public function testGetRepository_notAttached()
	{
		$this->repo->getRepository('foo');
	}

	/**
	 * @covers Csfd\Repositories\Repository::getRepository
	 * @expectedException Csfd\InternalException
	 */
	public function testGetRepository_repoUnset()
	{
		$this->repo->setContainer($this->getMockContainer());
		$this->repo->getRepository('foo');
	}

	/**
	 * @covers Csfd\Repositories\Repository::getRepository
	 * @expectedException Csfd\InternalException
	 */
	public function testGetRepository_repoInvalid()
	{
		$this->repo->setContainer($this->getMockContainer());
		$this->repo->getRepository('invalid');
	}

	/** @covers Csfd\Repositories\Repository::getRepository */
	public function testGetRepository()
	{
		$container = $this->getMockContainer();
		$this->repo->setContainer($container);
		$users = $this->repo->getRepository('users');
		$this->assertSame($container->users, $users);
	}

	/**
	 * @covers Csfd\Repositories\Repository::get()
	 * @expectedException Csfd\InternalException
	 */
	public function testGet_notSet()
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
	public function testGetParser_notSet()
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
