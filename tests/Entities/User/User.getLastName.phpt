<?php

require __DIR__ . '/bootstrap.user.php';

Assert::same('_surname_', $entity->getLastName());
