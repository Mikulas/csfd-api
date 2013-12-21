<?php

use Csfd\Parsers;
use Csfd\Repositories\Users as UsersRepository;
use Csfd\Networking\UrlBuilder;
use Csfd\Networking\Request;
use Csfd\Networking\RequestFactory;
use Csfd\Authentication\Authenticator;

require __DIR__ . '/../bootstrap.php';

covers('Csfd\Repositories\Users');


$auth = new MockAuthenticator;

$users = new UsersRepository($auth, UrlBuilder::factory($urlsPath), new RequestFactory);
$users->setParserClass('Csfd\Parsers\User');
$users->setEntityClass($class = 'Csfd\Entities\User');

Assert::type($class, $users->get(1));

Assert::exception(function() use ($users) {
	$users->getAuthenticatedUser();
}, 'Csfd\Authentication\Exception');

$auth->userId = 1;

Assert::type('Csfd\Entities\AuthenticatedUser', $users->getAuthenticatedUser(1));
