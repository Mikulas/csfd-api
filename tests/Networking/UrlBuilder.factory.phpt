<?php

use Csfd\Networking\UrlBuilder;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Networking\UrlBuilder');

Assert::exception(function() {
	UrlBuilder::factory(__DIR__ . '/config-does-not-exist.yml');
}, 'Csfd\InternalException');

Assert::exception(function() {
	UrlBuilder::factory(__DIR__ . '/fixtures/invalid-empty.yml');
}, 'Csfd\InternalException');

Assert::exception(function() {
	UrlBuilder::factory(__DIR__ . '/fixtures/invalid-missing-root.yml');
}, 'Csfd\InternalException');

UrlBuilder::factory(__DIR__ . '/fixtures/valid-min.yml');
