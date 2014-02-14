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

	/** @covers Csfd\Entities\User::getRatings() */
	public function testGetRatings()
	{
		$ratings = $this->entity->getRatings(1);
		$this->assertContainsOnlyInstancesOf('Csfd\Collections\Rating', $ratings);
	}

}
