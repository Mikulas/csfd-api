<?php

use Csfd\Networking\UrlBuilder;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Networking\UrlBuilder');

$urlBuilder = UrlBuilder::factory(__DIR__ . '/fixtures/valid-min.yml');
Assert::same('rootVal/', $urlBuilder->getRoot());
