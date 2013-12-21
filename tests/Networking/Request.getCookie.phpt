<?php

use Csfd\Networking\Request;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Networking\Request');

$res = new Request('http://localhost/');
$headers = Access($res, '$headers');

$headers->set([]);
Assert::same('', $res->getCookie());

$headers->set([
	'set-cookie' => []
]);
Assert::same('', $res->getCookie());

$headers->set([
	'set-cookie' => ['foo=bar; path /; HTTP_ONLY', 'baz=qaz']
]);
Assert::same('foo=bar; baz=qaz', $res->getCookie());
