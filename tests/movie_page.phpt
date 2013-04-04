<?php

/**
 * Search
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$movie = $csfd->getMovie('289999'); // Silver Linings Playbook
var_dumP($movie);
