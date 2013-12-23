<?php

namespace Csfd\Entities;

use Csfd\Parsers;
use TestCase;


class UserTest extends TestCase
{

	const ID = 1337;

	private $entity;


	public function setUp()
	{
		$this->entity = new User(
			$this->getMockAuthenticator(),
			$this->getMockUrlBuilder(),
			new Parsers\User,
			$this->getMockRequestFactory(),
			self::ID
		);
	}

	/** @covers Csfd\Entities\User::getUrlKey() */
	public function testRequest()
	{
		$e = Access($this->entity);
		$this->assertInternalType('string', $e->getUrlKey('any property'));
	}

}
