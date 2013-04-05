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
		return Movie::fromPage($this->grabber->request(Grabber::GET, "film/$id"), $id);
	}



	public function getAuthor($id)
	{
		return Author::fromPage($this->grabber->request(Grabber::GET, "tvurce/$id"), $id);
	}

}
