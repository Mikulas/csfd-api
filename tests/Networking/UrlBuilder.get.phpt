<?php

use Csfd\Networking\UrlBuilder;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Networking\UrlBuilder');

$urlBuilder = UrlBuilder::factory(__DIR__ . '/fixtures/valid.yml');
Assert::same('rootVal/fooBarVal', $urlBuilder->get(['foo', 'bar']));
Assert::same('rootVal/baABCD', $urlBuilder->get(['baA', 'baB', 'baC', 'baD']));

Assert::exception(function() use ($urlBuilder) {
	$urlBuilder->get(['does-not-exist']);
}, 'Csfd\InternalException');

Assert::exception(function() use ($urlBuilder) {
	$urlBuilder->get(['foo', 'does-not-exist']);
}, 'Csfd\InternalException');
