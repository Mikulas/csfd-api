<?php

require __DIR__ . '/bootstrap.php';

covers('nothing'); // only builds executable lines for coverage

// untested files only
require_once __DIR__ . '/../src/Csfd/Authentication/Exception.php';
require_once __DIR__ . '/../src/Csfd/Entities/Exception.php';
require_once __DIR__ . '/../src/Csfd/Entities/Message.php';
require_once __DIR__ . '/../src/Csfd/Entities/User.php';
require_once __DIR__ . '/../src/Csfd/Exception.php';
require_once __DIR__ . '/../src/Csfd/InternalException.php';
require_once __DIR__ . '/../src/Csfd/Parsers/Authentication.php';
require_once __DIR__ . '/../src/Csfd/Parsers/Exception.php';
