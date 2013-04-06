<?php

namespace Csfd;


class Search
{

	const METHOD = 'hledat';


	const TYPE_MOVIE = 1;
	const TYPE_AUTHOR = 2;
	const TYPE_USER = 4;


	/** @var enum */
	protected $type;

	/** @var string */
	protected $filter;

	/** @var int|NULL */
	protected $filter_year;


	/** @var Grabber */
	protected $grabber;



	public function __construct(Grabber $grabber)
	{
		$this->grabber = $grabber;
	}



	public function movie($filter)
	{
		$this->type = self::TYPE_MOVIE;
		$this->filter = $filter;

		return $this;
	}



	public function author($filter)
	{
		$this->type = self::TYPE_AUTHOR;
		$this->filter = $filter;

		return $this;
	}



	public function user($filter)
	{
		$this->type = self::TYPE_USER;
		$this->filter = $filter;

		return $this;
	}



	public function year($year)
	{
		if ($this->type !== self::TYPE_MOVIE) {
			throw new \BadMethodCallException('Cannot filter authors by date.');
		}

		$this->filter_year = $year;

		return $this;
	}



	/**
	 * @return Movie[]|Author[]
	 */
	public function find()
	{
		if (!$this->filter) {
			throw new \InvalidArgumentException('Cannot filter with empty query.');
		}

		$html = $this->grabber->request(Grabber::GET, self::METHOD, ['q' => $this->filter]);

		if ($this->type === self::TYPE_MOVIE) {
			$movies = [];

			if ($html->find('#rating')) { // only one result found and redirected
				return [Movie::fromPage($html)];
			}

			foreach ($html->find('#search-films li') as $movie) {
				$movies[] = Movie::fromSearch($movie);
			}

			return $movies;

		} else if ($this->type === self::TYPE_AUTHOR) {
			$authors = [];

			foreach ($html->find('#search-creators li') as $author) {
				$authors[] = Author::fromSearch($author);
			}

			return $authors;

		} else if ($this->type === self::TYPE_USER) {
			$users = [];

			foreach ($html->find('#search-users li') as $user) {
				$users[] = User::fromSearch($user);
			}

			return $users;

		} else {
			throw new NotImplementedException();
		}
	}

}
