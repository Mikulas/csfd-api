<?php

use Csfd\Parsers;
use Csfd\Repositories\Repository;
use Csfd\Networking\UrlBuilder;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\Request;
use Csfd\Authentication\Authenticator;
use Csfd\Entities\Entity;

require __DIR__ . '/../bootstrap.php';

class TestEntity extends Entity
{
	public function _getFoo()
	{
		return 'test';
	}
}

class MockRequest extends Request
{
	public function __construct() {}
}

class MockAuthenticator extends Authenticator
{
	public function getCookie()
	{
		return '';
	}
	public function getUserId()
	{
		return 1;
	}
}


$urlBuilder = UrlBuilder::factory($urlsPath);
$requestFactory = new RequestFactory;
$requestFactory->setRequestClass('MockRequest');
$parser = new Parsers\User;
$auth = new MockAuthenticator($urlBuilder, $parser,
	new Parsers\Authentication, $requestFactory);

$entity = Access(new TestEntity($auth, $urlBuilder, $parser, $requestFactory));
