<?php

use Csfd\Parsers;
use Csfd\Search;
use Csfd\Networking\UrlBuilder;
use Csfd\Repositories\Users;
use Csfd\Repositories\Movies;

require __DIR__ . '/../bootstrap.php';

covers('Csfd\Search\Search');

$auth = new MockAuthenticator;
$urlBuilder = UrlBuilder::factory($urlsPath);
$requestFactory = new CachedRequestFactory;
$requestFactory->setRequestClass('Csfd\Networking\Request');

$users = new Users($auth, $urlBuilder, $requestFactory);
$users->setEntityClass('Csfd\Entities\User');
$users->setParserClass('Csfd\Parsers\User');
$movies = new Movies($auth, $urlBuilder, $requestFactory);
$movies->setEntityClass('Csfd\Entities\Movie');
$movies->setParserClass('Csfd\Parsers\Movie');

$search = new Search($urlBuilder, $requestFactory, new Parsers\Search, $users, $movies);
