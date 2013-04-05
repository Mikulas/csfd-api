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

	/** @var string */
	protected $place;

	/** @var string url */
	protected $portrait_url;

	/** @var string */
	protected $bio;

	/** @var mixed */
	protected $filmography;

	/** @var string url */
	protected $imdb_url;



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



	public static function fromPage($id, $html)
	{
		$author = new self($id);

		$name = $html->find('h1', 0)->innertext;
		$name = str_replace('</a>', '', $name); // bug in csfd markup
		$author->name = $name;

		$meta = $html->find('.info ul li', 0);
		if ($meta) {
			list($born, $place) = explode("<br />", $meta->innertext);
			$date = substr(trim($born), 5); // "nar. 17.4.1924"
			$author->born = new \DateTime($date);

			$place = trim($place);
			$pos = strrpos($place, ', ');
			$author->countries[] = substr($place, $pos + 2);
			$author->place = substr($place, 0, $pos);
		}

		$author->portrait_url = $html->find('img[alt=foto]', 0)->src;

		$author->bio = preg_replace('~(\s*<br />\s*)+~', "\n", $html->find('#action p', 0)->innertext);

		foreach ($html->find('#filmography div.ct-general') as $group) {
			list($c_type) = explode(" ", $group->find('h2', 0)->innertext);
			$type = '';
			switch (strToLower($c_type)) {
				case 'režijní':
					$type = 'director'; break;
				case 'scénáristická':
					$type = 'script'; break;
				case 'herecká':
					$type = 'actor'; break;
				case 'kameramanská':
					$type = 'camera'; break;
				case 'skladatelská':
					$type = 'soundtrack'; break;
				default:
					$type = $c_type;
			}

			foreach ($group->find('tr') as $node) {
				$movie = Movie::fromFilmography($node);
				if (!$movie)
					continue;

				$author->filmography[$type][] = $movie;
			}
		}

		$imdb = $html->find('ul.links a', 0);
		if ($imdb) {
			$author->imdb_url = $imdb->href;
		}

		return $author;
	}

}
