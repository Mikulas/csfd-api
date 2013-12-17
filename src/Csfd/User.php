<?php

namespace Csfd;


class User extends Entity
{

	const USER_CSFD = "\10"; // special "username" for csfd message bot

	protected $id;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder, Parsers\Parser $parser, $id)
	{
		parent::__construct($auth, $urlBuilder, $parser);
		$this->id = $id;
	}

	public function getProfile()
	{
		
	}

}
