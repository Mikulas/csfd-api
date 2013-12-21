<?php

use Csfd\Parsers;
use Csfd\Repositories\Repository;
use Csfd\Networking\UrlBuilder;
use Csfd\Networking\RequestFactory;
use Csfd\Authentication\Authenticator;

require __DIR__ . '/../bootstrap.php';

covers('Csfd\Repositories\Repository');

class TestRepository extends Repository {}


$urlBuilder = UrlBuilder::factory($urlsPath);
$requestFactory = new RequestFactory;
$auth = new Authenticator($urlBuilder, new Parsers\User,
	new Parsers\Authentication, $requestFactory);

$repository = new TestRepository($auth, $urlBuilder, $requestFactory);

Assert::exception(function() use ($repository) {
	$repository->getParser();
}, 'Csfd\InternalException');

$class = 'Csfd\Parsers\User';
$repository->setParserClass($class);
Assert::type($class, $repository->getParser());

Assert::exception(function() use ($repository) {
	$repository->get(1);
}, 'Csfd\InternalException');

$class = 'Csfd\Entities\User';
$repository->setEntityClass($class);
Assert::type($class, $repository->get(1));
