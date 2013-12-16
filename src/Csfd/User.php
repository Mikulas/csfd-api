<?php

namespace Csfd;


class User extends Entity
{

	const USER_CSFD = "\10"; // special "username" for csfd message bot

	private $userId;

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

	public function getProfile()
	{

	}

	/**
	 * @auth
	 */
	public function editProfile($text)
	{
		$res = $this->authRequest($this->getUrl('profileToken'));
		$token = $res->getContent()->filterXPath('//*[@name="_token_"]')->attr('value');

		$data = [
			'text' => $text,
			'ok' => 'Uložit',
			'_token_' => $token,
		];

		$res = $this->authRequest($this->getUrl('profileEdit'), $data, Request::POST);

		// TODO check status and return if ok
	}

}
