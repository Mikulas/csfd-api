<?php

require __DIR__ . '/bootstrap.user.php';

Assert::same('_about_', $entity->getAbout());
