<?php

namespace Csfd\Entities;

use Csfd\Authentication\Authenticator;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers\Parser;


/**
 * @method int getRating() 0..100
 * @method int|NULL getChartPosition() NULL if not in top 100
 * @method string getPosterUrl()
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

}
