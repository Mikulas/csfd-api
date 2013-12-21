<?php

require __DIR__ . '/bootstrap.user.php';

Assert::same('profile content <strong>bold</strong>', $entity->getProfile());
