<?php

use Tester\Assert;

require __DIR__ . '/bootstrap.php';


Assert::exception(function() use ($parser, $html) {
	$parser->getFormToken($html, 'no-existing-form');
}, 'Csfd\Parsers\Exception');

Assert::exception(function() use ($parser, $html) {
	$parser->getFormToken($html, 'form-without-token');
}, 'Csfd\Parsers\Exception');

$token = $parser->getFormToken($html, 'form-with-token');
Assert::equal('tokenValue', $token);
