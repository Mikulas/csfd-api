<?php

require __DIR__ . '/bootstrap.movie.php';

Assert::same('http://img.csfd.cz/posters/22/228329_dvd_1.jpg?h180', $entity->getPosterUrl());
