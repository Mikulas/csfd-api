<?php

use Csfd\Parsers;
use Csfd\Parsers\Exception;

require __DIR__ . '/bootstrap.parser.php';

Assert::exception(function() use ($parser) {
	$parser->getIdFromUrl('http://example.com');
}, 'Csfd\Parsers\Exception', Exception::URL_ID_NOT_FOUND);

Assert::exception(function() use ($parser) {
	$parser->getIdFromUrl('http://www.csfd.cz/invalidkey/14036-klara-jandova/');
}, 'Csfd\Parsers\Exception', Exception::URL_ID_NOT_FOUND);

Assert::same(268216, $parser->getIdFromUrl('http://www.csfd.cz/uzivatel/268216-mikulasdite/'));
Assert::same(267763, $parser->getIdFromUrl('http://www.csfd.cz/film/267763-pelisky-slavnych/'));
Assert::same(14036, $parser->getIdFromUrl('http://www.csfd.cz/tvurce/14036-klara-jandova/'));
