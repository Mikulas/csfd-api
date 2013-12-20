<?php

use Csfd\Networking\Request;

require __DIR__ . '/bootstrap.php';


$res = new Request('http://localhost/', ['foo' => 'bar'], Request::GET, 'test=test');

Assert::contains('<!DOCTYPE', $res->getContent());
Assert::same(200, $res->getStatusCode());
