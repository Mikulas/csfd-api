<?php

use Csfd\Csfd;

require __DIR__ . '/bootstrap.php';

$csfd = new Csfd;
// $csfd->authenticate('MikulasDite', 'csfd.asdads');
$csfd->authenticate('MikulasDite', 'csfd.Rlf_158');
$csfd->getAuthenticatedUser();

$user = $csfd->dbg_getUser();
$user->editProfile('new text: ' . mt_rand(1e6, 1e7 - 1));
