<?php

require __DIR__ . '/bootstrap.search.php';

// empty
Assert::same([], getIds($search, 'thisAuthorDoesNotExist'));

// single result, redirect
Assert::same([10572], getIds($search, 'Chiwetel Ejiofor'));

// both full and short results
Assert::same([
		32165, 65317, 98882, 96675, 4011, 67584,
		88502, 98386, 100647, 72405, 68621, 8814,
	], getIds($search, 'Pope'));

function getIds($search, $query)
{
	$ids = [];
	foreach ($search->findAuthor($query) as $user)
	{
		$ids[] = $user->getId();
	}
	return $ids;
}
