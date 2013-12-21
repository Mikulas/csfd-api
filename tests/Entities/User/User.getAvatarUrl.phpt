<?php

require __DIR__ . '/bootstrap.user.php';

$url = 'http://img.csfd.cz/files/images/user/avatars/158/155/158155401_a5d9bc.jpg?w60h80crop';
Assert::same($url, $entity->getAvatarUrl());
