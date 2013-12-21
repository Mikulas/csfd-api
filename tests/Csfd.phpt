<?php

use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';

covers('Csfd\Csfd');


$csfd = Csfd::create();
Assert::type('Csfd\Csfd', $csfd);

// test it does not throw exception
$csfd->authenticate('test', 'test');