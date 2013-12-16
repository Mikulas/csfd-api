<?php

namespace Csfd;

use Symfony\Component\Yaml\Yaml;


class Csfd
{

	private $config;
	private $urlBuilder;
	private $authenticator;
	private $userId;

	public function __construct()
	{
		$raw = file_get_contents(__DIR__ . '/config.yml');
		$this->config = Yaml::parse($raw);
		$this->urlBuilder = new UrlBuilder($this->config);
		$this->authenticator = new Authenticator($this->urlBuilder);
	}

	// TODO methods with @auth should automatically include
	// cookie from authenticator get cookie

	// TODO @auth methods are only called on authenticated user?
	// TODO provide a method for getting authenticated user

	/**
	 * @auth
	 * @return User
	 */
	public function getAuthenticatedUser()
	{
		// TODO move to another method
		if (!$this->userId)
		{
			$res = new Request($this->config['url']['root'], [], Request::GET, $this->authenticator->getCookie());
			$url = $res->getContent()->filterXPath('//*[@id="user-menu"]/a')->attr('href');
			$parser = new Parsers\User;
			$this->userId = $parser->getIdFromUrl($url);
			echo "set user id\n";
			var_dump($this->userId);
		}
		// TODO create user from userId
	}

	/**
	 * Credentials are verified upon first authenticated request
	 * @param string $username
	 * @param string $password
	 */
	public function authenticate($username, $password)
	{
		$this->authenticator->setCredentials($username, $password);

		// TODO dbg, remove
		$this->getAuthenticatedUser();
		$this->urlBuilder->addMap('userId', $this->userId);
	}

	public function dbg_validateCrendentials()
	{
		$this->authenticator->getCookie();
	}

	public function dbg_getUser()
	{
		return new User($this->authenticator, $this->urlBuilder);
	}

}
