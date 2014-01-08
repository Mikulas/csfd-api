<?php

namespace Csfd\Authentication;

use Csfd\Networking;
use Csfd\Networking\Request;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlAccess;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers;


class Authenticator
{

	use UrlAccess;

	private $cookie;
	private $username;
	private $password;

	/** @var Parsers\User */
	private $userParser;

	/** @var Parsers\Authentication */
	private $authParser;

	/** @var Networking\RequestFactory */
	private $requestFactory;

	/**
	 * id of authenticated user
	 * @var int|NULL
	 */
	private $userId;

	public function __construct(UrlBuilder $urlBuilder, Parsers\User $userParser, Parsers\Authentication $authParser, RequestFactory $requestFactory)
	{
		$this->setUrlBuilder($urlBuilder);
		$this->userParser = $userParser;
		$this->authParser = $authParser;
		$this->requestFactory = $requestFactory;
	}

	public function setCredentials($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

	public function getCookie()
	{
		if ($this->cookie)
		{
			return $this->cookie;
		}

		if (!$this->username || !$this->password)
		{
			throw new Exception('Credentials not set. Hint: call setCredentials().', Exception::CREDENTIALS_NOT_SET);
		}

		$url = $this->getUrl('login');

		$args = [
			'username' => $this->username,
			'password' => $this->password,
			'permanent' => 'on',
			'ok' => 'Přihlásit',
			'__REFERER__' => 'http://www.csfd.cz/',
		];

		$res = $this->requestFactory->create($url, $args, Request::POST);

		if ($this->authParser->containsError($res->getContent()))
		{
			throw new Exception('Invalid credentials.', Exception::INVALID_CREDENTIALS);
		}
		
		$this->cookie = $res->getCookie();

		$this->setUserId();
		$this->urlBuilder->getMap()->insert('userId', $this->userId);

		return $this->cookie;
	}

	/**
	 * @return int|NULL User id of authenticated user or NULL
	 */
	public function getUserId()
	{
		if ($this->userId === NULL)
		{
			try {
				$this->getCookie(); // triggers setter

			} catch (Exception $e) {
				throw new Exception('Not authenticated. Hint: call setCredentials.', Exception::NOT_AUTHENTICATED, $e);
			}
		}
		return $this->userId;
	}

	private function setUserId()
	{
		$cookie = $this->cookie; // intentionally not calling getCookie()
		$res = $this->requestFactory->create($this->urlBuilder->getRoot(), [], Request::GET, $cookie);

		$this->userId = $this->userParser->getCurrentUserId($res->getContent());
	}

}
