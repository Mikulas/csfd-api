<?php

namespace Csfd\Entities;

use Csfd\Parsers;
use TestCase;


class EntityTest extends TestCase
{

	const ID = 1337;

	private $entity;
	private $parser;


	/** @covers Csfd\Entities\Entity::__construct */
	public function setUp()
	{
		$this->parser = new TestParser;
		$this->entity = new TestEntity(
			$this->getMockAuthenticator(),
			$this->getMockUrlBuilder(),
			$this->parser,
			$this->getMockRequestFactory(),
			self::ID
		);
	}

	/** @covers Csfd\Entities\Entity::request() */
	public function testRequest()
	{
		$this->assertInstanceOf('Csfd\Networking\Request', $this->entity->request('random url'));
	}

	/** @covers Csfd\Entities\Entity::authRequest() */
	public function testAuthRequest()
	{
		$this->assertInstanceOf('Csfd\Networking\Request', $this->entity->authRequest('random url'));
	}

	/** @covers Csfd\Entities\Entity::getParser() */
	public function testGetParser()
	{
		$e = Access($this->entity);
		$this->assertSame($this->parser, $e->getParser());
	}

	/** @covers Csfd\Entities\Entity::getUrlKey() */
	public function testGetUrlKey()
	{
		$e = Access($this->entity);
		$this->assertInternalType('string', $e->getUrlKey('anything'));
	}

	/** @covers Csfd\Entities\Entity::getId() */
	public function testGetId()
	{
		$this->assertInternalType('int', $this->entity->getId());
		$this->assertSame(self::ID, $this->entity->getId());
	}

	/** @covers Csfd\Entities\Entity::_get() */
	public function testGet()
	{
		$this->assertSame(TestEntity::RETVAL, $this->entity->getFoo());
		$this->assertSame(TestParser::RETVAL, $this->entity->getBar());
	}

	/**
	 * @covers Csfd\Entities\Entity::setRepository
	 * @covers Csfd\Entities\Entity::getRepository
	 */
	public function testSetGetRepository()
	{
		$e = Access($this->entity);
		$repo = $this->getUsersRepository();
		$e->setRepository($repo);
		$this->assertSame($repo, $e->repository);
		$this->assertSame($repo, $e->getRepository());
	}

	/**
	 * @covers Csfd\Entities\Entity::getRepository
	 * @expectedException Csfd\InternalException
	 */
	public function testGetRepository_notSet()
	{
		$e = Access($this->entity);
		$e->getRepository();
	}

	/**
	 * @covers Csfd\Entities\Entity::getRepository
	 * @expectedException Csfd\InternalException
	 */
	public function testGetRepository_byName_notSet()
	{
		$e = Access($this->entity);
		$e->getRepository('whatever');
	}

	/** @covers Csfd\Entities\Entity::getRepository */
	public function testGetRepository_byName()
	{
		$e = Access($this->entity);

		$repo = $this->getUsersRepository();
		$repo->setContainer($this->getMockContainer());

		$e->setRepository($repo);

		$this->assertInstanceOf('Csfd\Repositories\Authors', $e->getRepository('authors'));
	}

}

class TestEntity extends Entity
{

	const RETVAL = 'test 089124';

	public function _getFoo()
	{
		return self::RETVAL;
	}
}

class TestParser extends Parsers\Parser
{

	const RETVAL = 'test 982452';

	public function getBar()
	{
		return self::RETVAL;
	}
}
