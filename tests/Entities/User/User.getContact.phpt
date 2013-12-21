<?php

require __DIR__ . '/bootstrap.user.php';

Assert::same([
	'icq' => '_icq_',
	'skype' => 'skypeNick',
	'jabber' => '_jabber_',
	'msn' => '_msn_',
	'yahoo' => '_yahoo_',
	'twitter' => '_twitter_',
], $entity->getContact());
