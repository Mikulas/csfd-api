<?php

use Csfd\Csfd;
use Csfd\Request;


require __DIR__ . '/bootstrap.php';

$csfd = Csfd::create();
// $csfd->authenticate('MikulasDite', 'csfd.asdads');
$csfd->authenticate('MikulasDite', 'csfd.Rlf_158');
$user = $csfd->users->getAuthenticatedUser();
$user->editProfile('new text: ' . mt_rand(1e6, 1e7 - 1) . ' ' . date('c'));

echo "requests: " . Request::$_dbgCount . "\n";
