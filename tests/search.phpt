<?php

/**
 * Search
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Mikulas\Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$res = $csfd->findMovie('rather');

$expected = unserialize(file_get_contents(__DIR__ . '/search_result.dat'));
Assert::equal($res, $expected);
