<?php

use Csfd\Repositories\Users;


class UsersTest extends TestCase
{

	private $repo;

	public function setUp()
	{
		$this->repo = new Users(
			$this->getMockAuthenticator(),
			$this->getMockUrlBuilder(),
			$this->getMockRequestFactory()
		);
		$this->repo->setParserClass('Csfd\Parsers\User');
		$this->repo->setEntityClass('Csfd\Entites\User');
	}

	/**
	 * @covers Csfd\Repositories\Users::getAuthenticatedUser()
	 * @expectedException Csfd\Authentication\Exception
	 * @expectedExceptionCode Csfd\Authentication\Exception::NOT_AUTHENTICATED
	 */
	public function testGetAuthenticatedUserNotAuthenticated()
	{
		$this->repo->getAuthenticatedUser();
	}

	/**
	 * @covers Csfd\Repositories\Users::getAuthenticatedUser()
	 */
	public function testGetAuthenticatedUser()
	{
		$auth = $this->getMockAuthenticator();
		$auth->userId = $id = 1337;
		$user = $this->repo->getAuthenticatedUser();
		$this->assertInstanceOf('Csfd\Entities\AuthenticatedUser', $user);
		$this->assertSame($id, $user->getId());
	}

}
