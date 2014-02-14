<?php

namespace Csfd\Entities;

use Csfd\Collections\Ratings;


/**
 * @method string getProfile() html
 * @method string getUsername()
 * @method string getFirstName()
 * @method string getLastName()
 * @method string getLocation()
 * @method string getAbout()
 * @method array getContact() [method => string]
 * @method int getPoints()
 * @method \DateTime getRegistered()
 * @method \DateTime getLastActivity() REQUIRES AUTH
 * @method string getAvatarUrl() url
 */
class User extends Entity
{

	const USER_CSFD = "\x10"; // special "username" for csfd message bot

	protected function getUrlKey($property)
	{
		return 'profile';
	}

	/**
	 * @param int $page 1..n
	 * @return Rating[]
	 */
	public function getRatings($page)
	{
		$vars = ['entityId' => $this->id, 'page' => $page];
		$html = $this->request($this->getUrl('ratings', $vars))->getContent();
		
		$ratings = [];
		foreach ($this->getParser()->getRatings($html) as list($id, $rating, $date))
		{
			$movie = $this->getRepository('movies')->get($id);
			$ratings[] = new Rating($movie, $rating, $date);
		}

		return $ratings;
	}

}
