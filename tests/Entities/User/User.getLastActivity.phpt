<?php

require __DIR__ . '/bootstrap.user.php';

Tester\Environment::skip('not implemented');

$time = $entity->getLastActivity();
Assert::type('DateTime', $time);

$exp = new DateTime('2013/12/20 21:51');
Assert::same($exp->format('r'), $exp->format('r'));

// TODO requires time
