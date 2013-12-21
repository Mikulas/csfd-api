<?php

use Csfd\Parsers;
use Csfd\Repositories\Repository;
use Csfd\Networking\UrlBuilder;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\Request;
use Csfd\Authentication\Authenticator;
use Csfd\Entities\Entity;

require __DIR__ . '/../bootstrap.php';

covers('Csfd\Entities\Entity');

class TestEntity extends Entity
{
	public function _getFoo()
	{
		return 'test';
	}
}

$urlBuilder = UrlBuilder::factory($urlsPath);
$requestFactory = new RequestFactory;
$requestFactory->setRequestClass('MockRequest');
$parser = new Parsers\User;
$auth = new MockAuthenticator;

$entity = Access(new TestEntity($auth, $urlBuilder, $parser, $requestFactory, 1));
