<?php

use Csfd\Parsers;
use Csfd\Networking\UrlBuilder;
use Csfd\Entities\Movie;

require __DIR__ . '/../../bootstrap.php';

covers('Csfd\Entities\Movie');

$id = getConfig()['movie']['id'];

$urlBuilder = UrlBuilder::factory($urlsPath);
$urlBuilder->addMap('userId', $id);
$requestFactory = new CachedRequestFactory;
$requestFactory->setRequestClass('Csfd\Networking\Request');
$parser = new Parsers\Movie;
$auth = new MockAuthenticator;

$entity = new Movie($auth, $urlBuilder, $parser, $requestFactory, $id);
