<?php

use Csfd\Parsers\Exception;

require __DIR__ . '/bootstrap.parser.php';


Assert::exception(function() use ($parser, $html) {
	$parser->getFormToken($html, 'no-existing-form');
}, 'Csfd\Parsers\Exception', Exception::FORM_NOT_FOUND);

Assert::exception(function() use ($parser, $html) {
	$parser->getFormToken($html, 'form-without-token');
}, 'Csfd\Parsers\Exception', Exception::TOKEN_NOT_FOUND);

$token = $parser->getFormToken($html, 'form-with-token');
Assert::equal('tokenValue', $token);
