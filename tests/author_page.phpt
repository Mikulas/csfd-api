<?php

/**
 * Author page
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$res = $csfd->getAuthor(53547); // Lawrence
$res = $csfd->getAuthor(2120); // Tarantino
var_dump($res);
