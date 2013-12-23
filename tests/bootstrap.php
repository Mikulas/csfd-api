<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('', __DIR__ . '/mocks');

CachedRequestFactory::$tempDir = __DIR__ . '/temp';

require __DIR__ . '/TestCase.php';
