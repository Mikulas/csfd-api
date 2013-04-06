<?php

/**
 * Search
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$user = $csfd->getUser('268216'); // MikulasDite
var_dumP($user);
