<?php

namespace Csfd;


class Author extends Serializable
{

	/** @var int */
	protected $id;

	/** @var string */
	protected $name;

	/** @var string[] */
	protected $roles;

	/** @var string[] */
	protected $countries;

	/** @var \DateTime */
	protected $born;

	/** @var string url */
	protected $portrait_url;



	public function __construct($id)
	{
		$this->id = $id;
	}



	private function getApiUrl()
	{
		return "/author/{$this->id}";
	}



	private function getCsfdUrl()
	{
		return "http://www.csfd.cz/tvurce/{$this->id}";
	}



	public function jsonSerialize()
	{
		$data = parent::jsonSerialize();

		$data['api_url'] = $this->getApiUrl();
		$data['csfd_url'] = $this->getCsfdUrl();

		return $data;
	}



	public function setName($name)
	{
		$this->name = $name;
	}



	public static function fromSearch($html)
	{
		$id = Helper::parseIdFromUrl($html->find('a', 0)->href);
		$author = new self($id);

		$name = $html->find('a', 1);
		if ($name) {
			$author->name = $name->innertext;
		} else {
			$author->name = $html->find('a', 0)->innertext;
		}

		$p0 = $html->find('p', 0);
		if ($p0) {
			$author->roles = explode(' / ', $p0->innertext);
		}

		$p1 = $html->find('p', 1);
		if ($p1) {
			$date = substr($p1->innertext, 5); // "nar. 17.4.1924"
			$author->born = new \DateTime($date);
		}

		$p2 = $html->find('p', 2);
		if ($p2) {
			$author->countries = explode(', ', $p2->innertext);
		}

		$portrait = $html->find('img[alt=foto]', 0);
		if ($portrait) {
			$author->portrait_url = $portrait->src;
		}

		return $author;
	}

}
