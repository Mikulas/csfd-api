<?php

/**
 * Search
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$movie = $csfd->getMovie(293006); // Great Gatsby

var_dump($movie->names);
Assert::true(isset($movie->names['en']));
