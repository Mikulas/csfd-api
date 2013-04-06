<?php

/**
 * Html grabber
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
//$res = $csfd->findMovie('kartotéka');
//var_dump(json_encode($res));
$res = $csfd->getUser(1);
var_dump(json_encode($res));
