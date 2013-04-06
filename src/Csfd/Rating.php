<?php

namespace Csfd;


class Rating extends Serializable
{

	/** @var int */
	protected $rating;

	/** @var string */
	protected $date;

	/** @var Movie */
	protected $movie;



	public function __construct($rating, \DateTime $date, Movie $movie)
	{
		$this->rating = (int) $rating;
		$this->date = $date;
		$this->movie = $movie;
	}



	public function jsonSerialize()
	{
		$data = parent::jsonSerialize();

		$data['date'] = $this->date->format('c');

		return $data;
	}

}
