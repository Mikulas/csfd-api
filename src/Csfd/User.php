<?php

namespace Csfd;


class User extends Entity
{

	const USER_CSFD = "\10"; // special "username" for csfd message bot

	protected $id;

	public function __construct(Authenticator $auth, UrlBuilder $urlBuilder, $id)
	{
		parent::__construct($auth, $urlBuilder);
		$this->id = $id;
	}

	public function getProfile()
	{

	}

}
