<?php

use Csfd\Networking\RequestFactory;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Networking\RequestFactory');

$rf = new RequestFactory;

Assert::exception(function() use ($rf) {
	$rf->create('url');
}, 'Csfd\InternalException');

$rf->setRequestClass('MockRequest');
Assert::type('MockRequest', $rf->create('url'));
