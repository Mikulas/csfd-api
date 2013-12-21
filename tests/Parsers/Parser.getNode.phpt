<?php

use Csfd\Parsers\Exception;

require __DIR__ . '/bootstrap.parser.php';


$found = $parser->getNode($html, '//*[@id="foo"]');
Assert::equal(1, $found->count());

Assert::exception(function() use ($parser, $html) {
	$parser->getNode($html, '//*[@id="doesNotExist"]');
}, 'Csfd\Parsers\Exception', Exception::NODE_NOT_FOUND);
