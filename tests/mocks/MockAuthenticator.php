<?php

use Csfd\Authentication\Authenticator;
use Csfd\Authentication\Exception;


class MockAuthenticator extends Authenticator
{

	public $cookie;
	public $userId;

	public function __construct() {}

	public function getCookie()
	{
		return $this->cookie;
	}

	public function getUserId()
	{
		if (!$this->userId)
		{
			throw new Exception(NULL, Exception::NOT_AUTHENTICATED);
		}
		return $this->userId;
	}

}
