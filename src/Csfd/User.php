<?php

namespace Csfd;


class User extends Serializable
{

	/** @var int */
	protected $id;

	/** @var string */
	protected $name;

	/** @var string */
	protected $username;

	/** @var string url */
	protected $portrait_url;

	/** @var \DateTime */
	protected $registered;

	/** @var mixed */
	protected $ratings;

	/** @var int */
	protected $total_ratings;




	public function __construct($id)
	{
		$this->id = (int) $id;
	}



	private function getApiUrl()
	{
		return "/user/{$this->id}";
	}



	private function getCsfdUrl()
	{
		return "http://www.csfd.cz/uzivatel/{$this->id}";
	}



	public function jsonSerialize()
	{
		$data = parent::jsonSerialize();

		$data['api_url'] = $this->getApiUrl();
		$data['csfd_url'] = $this->getCsfdUrl();

		return $data;
	}



	public static function fromSearch($html)
	{
		$id = Helper::parseIdFromUrl($html->find('a', 0)->href);
		$user = new self($id);

		$name = $html->find('a', 1);
		if ($name) {
			$user->name = $name->innertext;
		} else {
			$user->name = $html->find('a', 0)->innertext;
		}

		$portrait = $html->find('img.avatar', 0);
		if ($portrait) {
			$user->portrait_url = $portrait->src;
		}

		return $user;
	}



	public static function fromPage($html, $id = NULL)
	{
		if ($id === NULL) {
			$id = Helper::parseIdFromUrl($html->find('link[rel=canonical]', 0)->href);
		}
		$user = new self($id);

		$html = $html->find('.page-content', 0);

		$user->username = $html->find('h2', 0)->innertext;
		$user->name = $html->find('h3', 0)->innertext;

		$user->points = (int) explode(" ", $html->find('p.points', 0)->innertext)[0];

		$user->portrait_url = $html->find('img.avatar', 0)->src;

		$info = trim($html->find('.activity', 0)->innertext); // Na ČSFD.cz od: 4.12.2010&nbsp;&nbsp;11:36
		list($date, $time) = explode('&nbsp;&nbsp;', htmlentities(substr($info, 15)));
		$user->registered = new \DateTime("$date $time");

		$user->total_ratings = (int) substr($html->find('.ui-sidebar-menu .active .count', 0)->innertext, 1, -1);

		$ratings = [];
		foreach ($html->find('.ratings tr') as $node) {
			$movie = Movie::fromRating($node);

			$stars = $node->find('td img', 0);
			if ($stars) {
				$rating = strlen($stars->alt);
			} else {
				$rating = 0;
			}

			$date = new \DateTime($node->find('td', 2)->innertext);

			$ratings[] = (object) [
				'rating' => $rating,
				'date' => $date,
				'movie' => $movie,
			];
		}
		$user->ratings = $ratings;

		return $user;
	}

}
