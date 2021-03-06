<?php

namespace Csfd\Repositories;

use Csfd\Entities\AuthenticatedUser;


class Users extends Repository
{

	public function getAuthenticatedUser()
	{
		$id = $this->authenticator->getUserId();
		return new AuthenticatedUser($this->authenticator, $this->urlBuilder, $this->getParser(), $this->requestFactory, $id);
	}

}
