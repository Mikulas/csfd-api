<?php

namespace Csfd\Entities;

use Csfd\Networking\Request;


class AuthenticatedUser extends User
{

	// 
	// @codeCoverageIgnoreStart
	// 

	/**
	 * @auth
	 * @return Message[]
	 */
	public function getReceivedMessages($page = NULL)
	{
		// same as byFolder but different url format
	}

	/**
	 * @auth
	 * @return Message[]
	 */
	public function getSentMessage($page = NULL)
	{

	}

	public function getMessageByFolder($folder)
	{

	}

	/**
	 * @auth
	 * @param string $username username or User::USER_CSFD
	 * @return Message[]
	 */
	public function getMessagesByUser($username)
	{

	}

	/**
	 * @auth
	 */
	public function editProfile($text)
	{
		$res = $this->authRequest($this->getUrl('profileToken'));
		$token = $this->getParser()->getFormToken($res->getContent(), 'frm-profileForm');

		$data = [
			'text' => $text,
			'ok' => 'Uložit',
			'_token_' => $token,
		];

		$res = $this->authRequest($this->getUrl('profileEdit'), $data, Request::POST);

		// TODO check status and return if ok
	}

	// 
	// @codeCoverageIgnoreEnd
	// 

	protected function getConfigKeys()
	{
		return ['user'];
	}

}
