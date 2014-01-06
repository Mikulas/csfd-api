<?php

namespace Csfd\Entities;

use Csfd\Authentication\Authenticator;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers\Parser;


/**
 * @method int getRating() 0..100
 * @method int|NULL getChartPosition() NULL if not in top 100
 * @method string getPosterUrl() poster url
 * @method array getPosterUrls() urls of all available posters
 * @method array getNames() [ISO 3166-1 alpha-2 code => name]
 * @method array getGenres()
 * @method array getOrigin() ISO 3166-1 alpha-2 codes
 * @method int getYear()
 * @method array getPlots() array of ['author' => User, 'plot' => string]
 * @method array getAuthors() array of [role => Author[]]
 * @method string getOfficialUrl() url
 * @method string getImdbUrl() url
 * @method string getImdbId()
 * @method array getRelatedMovies() next movies in series
 * @method array getSimilarMovies() similar plot or genre
 * @method array getTags()
 *
 * @method string getMyRating() REQUIRES AUTH
 */
class Movie extends Entity
{

	/**
	 * @auth
	 * @codeCoverageIgnore WIP
	 */
	public function _getMyRating()
	{
		
	}

	/**
	 * @codeCoverageIgnore WIP
	 */
	public function rate($rating)
	{
		if (!is_integer($rating) || $rating < 0 || $rating > 5)
		{
			throw new Exception('Rating must be an integer from {0, 1, 2, 3, 4, 5}.');
		}
		// TODO implement
	}

	/**
	 * Map id from parser to actual user entity
	 */
	public function _getPlots($html)
	{
		$plots = $this->getParser()->getPlots($html);
		foreach ($plots as &$node)
		{
			if ($node['user'] === NULL)
			{
				continue;
			}

			$node['user'] = $this->getRepository('users')->get($node['user']);
		}
		return $plots;
	}

	/**
	 * Map id from parser to actual user entity
	 */
	public function _getAuthors($html)
	{
		$authors = $this->getParser()->getAuthors($html);
		foreach ($authors as &$node)
		{
			foreach ($node as &$id)
			{
				$id = $this->getRepository('users')->get($id);
			}
		}
		return $authors;
	}

	/**
	 * Map id from parser to actual user entity
	 */
	public function _getRelatedMovies($html)
	{
		$movies = $this->getParser()->getRelatedMovies($html);
		foreach ($movies as &$movie)
		{
			$movie = $this->getRepository('movies')->get($movie);
		}
		return $movies;
	}

	/**
	 * Map id from parser to actual user entity
	 */
	public function _getSimilarMovies($html)
	{
		$movies = $this->getParser()->getSimilarMovies($html);
		foreach ($movies as &$movie)
		{
			$movie = $this->getRepository('movies')->get($movie);
		}
		return $movies;
	}


}
