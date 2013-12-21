<?php

use Csfd\Parsers;
use Csfd\Repositories\Users as UsersRepository;
use Csfd\Networking\UrlBuilder;
use Csfd\Networking\Request;
use Csfd\Networking\RequestFactory;
use Csfd\Authentication\Authenticator;

require __DIR__ . '/../bootstrap.php';

covers('Csfd\Repositories\Users');

class MockAuthenticator extends Authenticator
{
	public $loggedIn = FALSE;

	public function getUserId()
	{
		if (!$this->loggedIn)
		{
			throw new Csfd\Authentication\Exception;
		}
		return 1;
	}
}

$urlBuilder = UrlBuilder::factory($urlsPath);
$requestFactory = new RequestFactory;
$auth = new MockAuthenticator($urlBuilder, new Parsers\User,
	new Parsers\Authentication, $requestFactory);

$users = new UsersRepository($auth, $urlBuilder, $requestFactory);
$users->setParserClass('Csfd\Parsers\User');
$users->setEntityClass($class = 'Csfd\Entities\User');

Assert::type($class, $users->get(1));

Assert::exception(function() use ($users) {
	$users->getAuthenticatedUser();
}, 'Csfd\Authentication\Exception');

$auth->loggedIn = TRUE;

Assert::type('Csfd\Entities\AuthenticatedUser', $users->getAuthenticatedUser(1));
