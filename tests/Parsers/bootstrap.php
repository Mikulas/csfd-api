<?php

use Csfd\Parsers\Parser;

require __DIR__ . '/../bootstrap.php';


class TestParser extends Parser {}

$html = file_get_contents(__DIR__ . '/fixtures/form.html');
$parser = Access(new TestParser);
