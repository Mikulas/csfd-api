<?php

use Tester\Assert;
use Csfd\Networking\Request;

require __DIR__ . '/bootstrap.php';


$res = new Request('http://localhost/');
$headers = Access($res, '$headers');

$headers->set([
	'set-cookie' => []
]);
Assert::same('', $res->getCookie());

$headers->set([
	'set-cookie' => ['foo=bar; path /; HTTP_ONLY', 'baz=qaz']
]);
Assert::same('foo=bar; baz=qaz', $res->getCookie());
