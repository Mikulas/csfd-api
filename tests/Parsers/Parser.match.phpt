<?php

require __DIR__ . '/bootstrap.parser.php';

$res = $parser->match('x.Ax', '~\.(\w)~');
Assert::equal($res, ['.A', 'A']);
