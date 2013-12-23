<?php

namespace Csfd\Entities;

use Csfd\Parsers;
use TestCase;


class AuthenticatedUserTest extends TestCase
{

	const ID = 1337;

	private $entity;


	public function setUp()
	{
		$this->entity = new AuthenticatedUser(
			$this->getMockAuthenticator(),
			$this->getMockUrlBuilder(),
			new Parsers\User,
			$this->getMockRequestFactory(),
			self::ID
		);
	}

	/** @covers Csfd\Entities\AuthenticatedUser::getConfigKeys() */
	public function testGetConfigKeys()
	{
		$e = Access($this->entity);
		$keys = $e->getConfigKeys();
		$this->assertInternalType('array', $keys);
		foreach ($keys as $key)
		{			
			$this->assertInternalType('string', $key);
		}
	}

}
