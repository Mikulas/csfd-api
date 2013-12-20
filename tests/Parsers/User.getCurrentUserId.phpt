<?php

use Csfd\Parsers;
use Csfd\Parsers\Exception;

require __DIR__ . '/bootstrap.php';

$parser = new Parsers\User;

$invalidHtml = file_get_contents(__DIR__ . '/fixtures/form.html');
Assert::exception(function() use ($parser, $invalidHtml) {
	$parser->getCurrentUserId($invalidHtml);
}, 'Csfd\Parsers\Exception', Exception::USER_NODE_NOT_FOUND);

$html = file_get_contents(__DIR__ . '/fixtures/user.html');
Assert::same(268216, $parser->getCurrentUserId($html));
