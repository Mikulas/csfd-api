<?php

/**
 * Html grabber
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Grabber;

require __DIR__ . '/bootstrap.php';


$grabber = new Grabber();
$res = $grabber->request('GET', 'film/69991-test/');

Assert::equal(get_class($res), 'simple_html_dom');
Assert::true(count($res->find('#poster')) > 0);
Assert::true(count($res->find('#rating')) > 0);
