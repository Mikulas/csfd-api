<?php

use Csfd\Networking\RequestFactory;

require __DIR__ . '/bootstrap.php';


class MockRequest extends Csfd\Networking\Request
{

	function __construct() {}

}

$rf = new RequestFactory;

Assert::exception(function() use ($rf) {
	$rf->create();
}, 'Csfd\InternalException');

$rf->setRequestClass('MockRequest');
Assert::type('MockRequest', $rf->create());
