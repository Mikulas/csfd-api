<?php

namespace Csfd\Parsers;

use CachedRequestFactory;
use Csfd\Networking\UrlBuilder;
use DateTime;
use TestCase;


class UserParserTest extends TestCase
{

	private $parser;
	private $html;

	public function setUp()
	{
		$this->parser = new User;
		$builder = UrlBuilder::factory(__DIR__ . '/../../../src/Csfd/urls.yml');

		$auth = $this->getAuthenticator();
		$account = $this->getConfig()['account'];
		$auth->setCredentials($account['username'], $account['password']);

		$factory = new CachedRequestFactory;
		$factory->setRequestClass('Csfd\Networking\Request');
		$url = $builder->get(['entities', 'user', 'profile'], ['entityId' => $account['id']]);
		$this->html = $factory->create($url, NULL, NULL, $auth->getCookie())->getContent();
	}

	/** @covers Csfd\Parsers\User::getCurrentUserId() */
	public function testGetCurrentUserId()
	{
		$this->assertSame(460251, $this->parser->getCurrentUserId($this->html));
	}

	/**
	 * @covers Csfd\Parsers\User::getCurrentUserId()
	 * @expectedException Csfd\Parsers\Exception
	 * @expectedExceptionCode Csfd\Parsers\Exception::USER_NODE_NOT_FOUND
	 */
	public function testGetCurrentUserId_notFound()
	{
		$this->parser->getCurrentUserId('random html');
	}

	/** @covers Csfd\Parsers\User::getProfile() */
	public function testGetProfile()
	{
		$this->assertSame('profile content <strong>bold</strong>', $this->parser->getProfile($this->html));
	}

	/** @covers Csfd\Parsers\User::getUsername() */
	public function testGetUsername()
	{
		$this->assertSame('CsfdApiTest', $this->parser->getUsername($this->html));
	}

	/**
	 * @covers Csfd\Parsers\User::getNames()
	 * @covers Csfd\Parsers\User::getFirstName()
	 * @covers Csfd\Parsers\User::getLastName()
	 */
	public function testGetName()
	{
		$this->assertSame('_name_', $this->parser->getFirstName($this->html));
		$this->assertSame('_surname_', $this->parser->getLastName($this->html));
	}

	/**
	 * @covers Csfd\Parsers\User::getAboutNodes()
	 * @covers Csfd\Parsers\User::getLocation()
	 * @covers Csfd\Parsers\User::getAbout()
	 */
	public function testGetAbout()
	{
		$this->assertSame('okres Liberec', $this->parser->getLocation($this->html));
		$this->assertSame('_about_', $this->parser->getAbout($this->html));
	}

	/** @covers Csfd\Parsers\User::getContact() */
	public function testGetContact()
	{
		$exp = [
			'icq' => '_icq_',
			'skype' => 'skypeNick',
			'jabber' => '_jabber_',
			'msn' => '_msn_',
			'yahoo' => '_yahoo_',
			'twitter' => '_twitter_',
		];
		$this->assertSame($exp, $this->parser->getContact($this->html));
	}

	/** @covers Csfd\Parsers\User::getPoints() */
	public function testGetPoints()
	{
		$this->assertSame(0, $this->parser->getPoints($this->html));
	}

	/**
	 * @covers Csfd\Parsers\User::getActivity()
	 * @covers Csfd\Parsers\User::getRegistered()
	 */
	public function testGetRegistered()
	{
		$date = $this->parser->getRegistered($this->html);
		$this->assertInstanceOf('DateTime', $date);

		$exp = new DateTime('2013-12-20 21:51');
		$this->assertSame($exp->format('c'), $date->format('c'));
	}

	/** @covers Csfd\Parsers\User::getAvatarUrl() */
	public function testGetAvatarUrl()
	{
		$exp = 'http://img.csfd.cz/files/images/user/avatars/158/155/158155401_a5d9bc.jpg?w60h80crop';
		$this->assertSame($exp, $this->parser->getAvatarUrl($this->html));
	}

}
