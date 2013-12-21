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

	protected $id;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder, Parser $parser,
		RequestFactory $requestFactory, $id)
	{
		parent::__construct($auth, $urlBuilder, $parser, $requestFactory);
		$this->id = $id;

		// TODO check if user exists?
	}

	protected function _get($property, $args = NULL)
	{
		$args = func_get_args();
		array_shift($args);

		$html = $this->request($this->getUrl('profile'))->getContent();
		array_unshift($args, $html);

		$method = 'get' . ucFirst($property);
		return call_user_func_array([$this->getParser(), $method], $args);
	}

}
