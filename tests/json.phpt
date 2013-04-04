<?php

/**
 * Html grabber
 * @package Mikulas\Csfd
 */

use Tester\Assert;
use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';


$csfd = new Csfd();
$res = $csfd->findMovie('kartotéka');
Assert::equal(json_encode($res), '[{"id":336347,"year":1979,"poster_url":"http:\/\/img.csfd.cz\/assets\/images\/poster-free.png","names":{"cs":"Kartoteka"},"genres":["Drama"],"countries":["Polsko"],"authors":{"directors":[{"id":3435,"name":"Krzysztof Kieslowski"}],"actors":[{"id":22488,"name":"Gustaw Holoubek"},{"id":21604,"name":"Jan Ciecierski"}]}},{"id":297979,"year":2006,"poster_url":"http:\/\/img.csfd.cz\/posters\/29\/297979_1.jpg","names":{"cs":"Kartot\u00e9ka konspirac\u00ed"},"genres":["Dokument\u00e1rn\u00ed"],"countries":["Velk\u00e1 Brit\u00e1nie"]}]');
