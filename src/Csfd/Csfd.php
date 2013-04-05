<?php

namespace Csfd;


class Csfd
{

	/** @var Grabber */
	protected $grabber;



	public function __construct()
	{
		$this->grabber = new Grabber();
	}



	private function getSearch()
	{
		return new Search($this->grabber);
	}



	public function findMovie($filter)
	{
		return $this->getSearch()->movie($filter)->find();
	}



	public function findAuthor($filter)
	{
		return $this->getSearch()->author($filter)->find();
	}



	public function getMovie($id)
	{
		return Movie::fromPage($id, $this->grabber->request(Grabber::GET, "film/$id"));
	}



	public function getAuthor($id)
	{
		return Author::fromPage($id, $this->grabber->request(Grabber::GET, "tvurce/$id"));
	}

}
