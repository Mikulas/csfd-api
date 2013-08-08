<?php

/**
 * Search
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$res = $csfd->findMovie('bolek a lolek');

//file_put_contents(__DIR__ . '/search_result.dat', serialize($res));
$expected = unserialize(file_get_contents(__DIR__ . '/search_result.dat'));

Assert::equal($res, $expected);
