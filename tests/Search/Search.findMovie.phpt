<?php

require __DIR__ . '/bootstrap.search.php';

// empty
Assert::same([], getIds($search, 'emptySearch'));

// single result, redirect
Assert::same([267763], getIds($search, '"Pelíšky slavných"')); // intentionally inner quotes

// both full and short results
Assert::same([29491, 19372, 293006, 24915, 26359, 134362], getIds($search, 'gatsby'));

function getIds($search, $query)
{
	$ids = [];
	foreach ($search->findMovie($query) as $movie)
	{
		$ids[] = $movie->getId();
	}
	return $ids;
}
