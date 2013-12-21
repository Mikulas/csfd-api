<?php

require __DIR__ . '/bootstrap.entity.php';

Assert::type(get_class($parser), $entity->getParser());
