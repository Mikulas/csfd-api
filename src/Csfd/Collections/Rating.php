<?php

namespace Csfd\Collections;

use Csfd\Entities\Movie;
use DateTime;


class Rating
{

	/** @var Csfd\Entities\Movie */
	private $movie;

	/** @var 0..5 */
	private $rating;

	/** @var DateTime */
	private $date;


	public function __construct(Movie $movie, $rating, DateTime $date)
	{
		if (!is_integer($rating) || $rating < 0 || $rating > 5)
		{
			throw new RatingException('Rating must be an integer in range 0..5');
		}

		$this->movie = $movie;
		$this->rating = (int) $rating;
		$this->date = $date;
	}

	/** @return Csfd\Entities\Movie */
	public function getMovie()
	{
		return $this->movie;
	}

	/** @return int 0..5 */
	public function getRating()
	{
		return $this->rating;
	}

	/** @return DateTime */
	public function getDate()
	{
		return $this->date;
	}

}
