<?php

require __DIR__ . '/bootstrap.php';


$found = $parser->getNode($html, '//*[@id="foo"]');
Assert::equal(1, $found->count());

$found = $parser->getNode($html, '//*[@id="doesNotExist"]');
Assert::equal(0, $found->count());
