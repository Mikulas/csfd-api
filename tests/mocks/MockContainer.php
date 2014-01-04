<?php

use Csfd\Repositories\Users;
use Csfd\Repositories\Movies;
use Csfd\Repositories\Authors;


class MockContainer
{

	public $users;
	public $movies;
	public $authors;

	public function __construct(Users $users, Movies $movies, Authors $authors)
	{
		$this->users = $users;
		$this->movies = $movies;
		$this->authors = $authors;
	}

}
