<?php

namespace Csfd;

use Sunra\PhpSimple\HtmlDomParser;


class Movie extends Serializable
{

	/** @var int */
	protected $id;

	/** @var int */
	protected $year;

	/** @var string url */
	protected $poster_url;

	/** @var string[] */
	protected $names;

	/** @var string[] */
	protected $genres;

	/** @var string[] */
	protected $countries;

	/** @var float */
	protected $rating;

	/** @var string */
	protected $plot;

	/** @var string enum */
	protected $content_rating;

	/** @var int */
	protected $runtime;

	/** @var \DateTime[] */
	protected $releases;



	public function __construct($id)
	{
		$this->id = (int) $id;
	}



	private function getApiUrl()
	{
		return "/movie/{$this->id}";
	}



	private function getCsfdUrl()
	{
		return "http://www.csfd.cz/film/{$this->id}";
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
		$movie = new self($id);

		$poster = $html->find('img[alt=poster]', 0);
		$movie->poster_url = $poster ? $poster->src : NULL;

		$name = $html->find('.subject a', 0);
		if ($name) {
			$movie->names['cs'] = $name->innertext;
		} else {
			$movie->names['cs'] = $html->find('a.film', 0)->innertext;
		}

		$meta = $html->find('p', 0);
		if ($meta) {
			list($genres, $countries, $year) = explode(', ', $meta->innertext);
			$movie->genres = explode(' / ', $genres);
			$movie->countries = explode(' / ', $countries);
			$movie->year = (int) $year;
		}
		$movie->poster_url = $poster ? $poster->src : NULL;

		if (!$movie->year && $year = $html->find('.film-year', 0)) {
			$movie->year = (int) substr($year->innertext, 1, 4); // remove brackets
		}

		$crew = $html->find('p', -1);
		if ($crew) {
			$match = [];
			preg_match('~Režie:\s*(?P<directors>.*?)\s*(Hrají|$)~uis', $crew->innertext, $match);
			if ($match) {
				$movie->authors['directors'] = [];
				foreach (HtmlDomParser::str_get_html($match['directors'])->find('a') as $node) {
					$id = Helper::parseIdFromUrl($node->href);
					$author = new Author($id);
					$author->setName($node->innertext);
					$movie->authors['directors'][] = $author;
				}
			}

			$match = [];
			preg_match('~Hrají:\s*(?P<actors>.*?)\s*$~uis', $crew->innertext, $match);
			if ($match) {
				$movie->authors['actors'] = [];
				foreach (HtmlDomParser::str_get_html($match['actors'])->find('a') as $node) {
					$id = Helper::parseIdFromUrl($node->href);
					$author = new Author($id);
					$author->setName($node->innertext);
					$movie->authors['actors'][] = $author;
				}
			}
		}

		return $movie;
	}



	public static function fromPage($id, $html)
	{
		$movie = new self($id);

		$movie->names['cs'] = trim($html->find('h1', 0)->innertext);
		$names = $html->find('ul.names', 0);
		if ($names) {
			foreach ($names->find('li') as $node) {
				list($country) = explode(' ', $node->find('img', 0)->alt);
				$language = '';
				switch ($country) {
					case 'USA':
						$language = 'en'; break;
					default:
						$language = strToLower($country);
				}
				$movie->names[$language] = $node->find('h3', 0)->innertext;
			}
		}

		$poster = $html->find('img[alt=poster]', 0);
		if ($poster) {
			$movie->poster_url = $poster->src;
		}

		$genres = $html->find('.genre', 0);
		if ($genres) {
			$movie->genres = explode(' / ', $genres->innertext);
		}

		$meta = $html->find('.origin', 0);
		if ($meta) {
			list($countries, $year, $runtime) = explode(', ', $meta->innertext);
			$movie->countries = explode(' / ', $countries);
			$movie->year = (int) $year;
			$movie->runtime = $runtime;
		}

		$rating = $html->find('.average', 0);
		$movie->rating = $rating ? (int) $rating->innertext : NULL;

		foreach ($html->find('.creators div') as $node) {
			$cs_type = strToLower(substr($node->find('h4', 0)->innertext, 0, -1));
			$type = '';
			switch ($cs_type) {
				case 'režie':
					$type = 'directors'; break;
				case 'předloha':
					$type = 'original'; break;
				case 'scénář':
					$type = 'script'; break;
				case 'hudba':
					$type = 'sountrack'; break;
				case 'hrají':
					$type = 'actors'; break;
			}

			$movie->authors[$type] = [];
			foreach ($node->find('span a') as $anchor) {
				$id = Helper::parseIdFromUrl($anchor->href);
				$author = new Author($id);
				$author->setName($anchor->innertext);
				$movie->authors[$type][] = $author;
			}
		}

		$content = $html->find('.content ul li div', 0);
		if ($content) {
			$plot = [];
			foreach ($content->find('p') as $paragraph) {
				$text = trim($paragraph->innertext);
				if (strlen($text) > 10) { // skip meaningless glues
					$plot[] = $text;
				}
			}
			$movie->plot = implode("\n", $plot);
		}

		$content_rating = $html->find('.classification', 0);
		if ($content_rating) {
			$movie->content_rating = $content_rating->innertext;
		}

		foreach ($html->find('#releases table tr') as $release) {
			foreach (['cs' => 'ČR', 'sk' => 'SR'] as $key => $country) {
				if (mb_strpos($release->find('th', 0)->innertext, $country) !== FALSE) {
					$match = [];
					preg_match('~^\s*(?P<date>[\d.]+)~', $release->find('.date', 0)->innertext, $match);
					$movie->releases[$key] = new \DateTime($match['date']);
				}
			}
		}

		return $movie;
	}



	public static function fromFilmography($html)
	{
		if ($html->find('.ui-advert'))
			return NULL;

		$id = Helper::parseIdFromUrl($html->find('a', 0)->href);
		$movie = new self($id);

		$movie->name['cs'] = $html->find('a', 0)->innertext;
		$movie->year = (int) trim($html->find('th', 0)->innertext);

		return $movie;
	}

}
