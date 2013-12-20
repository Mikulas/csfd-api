<?php

namespace Csfd\Entities;

use Csfd\Authentication\Authenticator;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers\Parser;


class User extends Entity
{

	const USER_CSFD = "\10"; // special "username" for csfd message bot

	protected $id;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder, Parser $parser,
		RequestFactory $requestFactory, $id)
	{
		parent::__construct($auth, $urlBuilder, $parser, $requestFactory);
		$this->id = $id;
	}

	public function getProfile()
	{
		
	}

}
