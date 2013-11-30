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
Assert::true(property_exists($movie, 'plot'));

$csfd = new Csfd();
$movie = $csfd->getMovie(289999); // Silver Linings Playbook
Assert::true(property_exists($movie, 'plot'));
