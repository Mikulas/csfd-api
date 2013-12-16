<?php

/**
 * Search
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$ratings = $csfd->getUserAllRatings(366479); // only 1 page
var_dump($ratings);
