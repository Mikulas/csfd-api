<?php

namespace Csfd;


class Authenticator
{

	use UrlAccess;

	private $cookie;
	private $username;
	private $password;

	public function __construct(UrlBuilder $urlBuilder)
	{
		$this->setUrlBuilder($urlBuilder);
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
			throw new \Exception('self::setCredentials never called or called with empty args'); // TODO
		}

		$url = $this->getUrl('login');

		$args = [
			'username' => $this->username,
			'password' => $this->password,
			'permanent' => 'on',
			'ok' => 'Přihlásit',
			'__REFERER__' => 'http://www.csfd.cz/',
		];

		$res = Request::withoutRedirect($url, $args, Request::POST);

		file_put_contents('tmp.html', $res->getContent()->html());

		$errors = $res->getContent()->filterXPath('//*[@class="errors"]/ul/li');
		if ($errors->count())
		{
			throw new \Exception($errors->text()); // TODO
		}

		$this->cookie = $res->getCookie();
		return $this->cookie;
	}

}
