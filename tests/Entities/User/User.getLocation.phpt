<?php

require __DIR__ . '/bootstrap.user.php';

Assert::same('okres Liberec', $entity->getLocation());
