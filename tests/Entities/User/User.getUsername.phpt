<?php

require __DIR__ . '/bootstrap.user.php';

Assert::same('CsfdApiTest', $entity->getUsername());
