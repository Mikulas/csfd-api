<?php

use Csfd\Parsers;
use Csfd\Networking\UrlBuilder;
use Csfd\Networking\RequestFactory;
use Csfd\Entities\User;

require __DIR__ . '/../../bootstrap.php';

covers('Csfd\Entities\User');

$id = getConfig()['account']['id'];

$urlBuilder = UrlBuilder::factory($urlsPath);
$urlBuilder->addMap('userId', $id);
$requestFactory = new CachedRequestFactory;
$requestFactory->setRequestClass('Csfd\Networking\Request');
$parser = new Parsers\User;
$auth = new MockAuthenticator;

$entity = new User($auth, $urlBuilder, $parser, $requestFactory, $id);
