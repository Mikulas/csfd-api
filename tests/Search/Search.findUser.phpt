<?php

require __DIR__ . '/bootstrap.search.php';

// empty
Assert::same([], getIds($search, 'thisUserDoesNotExist'));

// single result, redirect
Assert::same([460251], getIds($search, 'CsfdApiTest'));

// both full and short results
Assert::same([
		402135, 111737, 75401, 117435, 339139, 92296,
		286671, 30174, 30000, 2638, 51341, 5062, 113366,
	], getIds($search, 'spike'));

function getIds($search, $query)
{
	$ids = [];
	foreach ($search->findUser($query) as $user)
	{
		$ids[] = $user->getId();
	}
	return $ids;
}
