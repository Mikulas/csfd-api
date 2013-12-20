<?php

use Tester\Assert;
use Csfd\Networking\UrlBuilder;

require __DIR__ . '/bootstrap.php';


$urlBuilder = UrlBuilder::factory(__DIR__ . '/fixtures/valid-min.yml');
Assert::same('rootVal/', $urlBuilder->getRoot());
