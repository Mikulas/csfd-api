<?php

require __DIR__ . '/bootstrap.user.php';

Assert::same('_name_', $entity->getFirstName());
