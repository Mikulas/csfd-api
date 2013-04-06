<?php

/**
 * Search user
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$res = $csfd->findUser("Mikulas Dite");
var_dump($res);
