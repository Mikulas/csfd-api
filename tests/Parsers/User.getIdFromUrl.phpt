<?php

use Csfd\Parsers;
use Csfd\Parsers\Exception;

require __DIR__ . '/bootstrap.php';

$parser = new Parsers\User;

Assert::exception(function() use ($parser) {
	$parser->getIdFromUrl('http://example.com');
}, 'Csfd\Parsers\Exception', Exception::USER_ID_NOT_FOUND);

Assert::same(268216, $parser->getIdFromUrl('http://www.csfd.cz/uzivatel/268216-mikulasdite/'));
