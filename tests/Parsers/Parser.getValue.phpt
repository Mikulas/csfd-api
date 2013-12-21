<?php

require __DIR__ . '/bootstrap.parser.php';


$res = $parser->getValue('x.Ax', '~\.(?P<value>\w)~');
Assert::equal($res, 'A');

Assert::exception(function() use ($parser) {
	$parser->getValue('x.Ax', '~\.(?P<foo>\w)~');
}, 'Csfd\InternalException');
