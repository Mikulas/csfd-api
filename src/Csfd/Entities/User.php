<?php

namespace Csfd\Entities;

use Csfd\Authentication\Authenticator;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers\Parser;


/**
 * @method string getProfile() html
 * @method string getUsername()
 * @method string getFirstName()
 * @method string getLastName()
 * @method string getLocation()
 * @method string getAbout()
 * @method array getContact() [method => string]
 * @method int getPoints()
 * @method DateTime getRegistered()
 * @method DateTime getLastActivity() REQUIRES AUTH
 * @method string getAvatarUrl() url
 */
class User extends Entity
{

	const USER_CSFD = "\10"; // special "username" for csfd message bot

	protected function getUrlKey($property)
	{
		return 'profile';
	}

}
