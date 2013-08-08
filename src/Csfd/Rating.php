<?php

namespace Csfd;


class Rating extends Serializable
{

	/** @var int */
	public $rating;

	/** @var string */
	public $date;

	/** @var Movie */
	public $movie;



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
