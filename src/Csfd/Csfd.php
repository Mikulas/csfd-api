<?php

namespace Csfd;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class Csfd
{

	/** @var ContainerBuilder */
	private $container;

	/** @var UsersRepository */
	public $users;

	public function __construct(ContainerBuilder $container)
	{
		$this->container = $container;

		$this->users = $this->container->get('users');
	}

	public static function create()
	{
		$container = new ContainerBuilder();
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
		$loader->load('services.yml');
		$container->setParameter('root', __DIR__);
		$container->compile();
		return new static($container);
	}

	// TODO methods with @auth should automatically include
	// cookie from authenticator get cookie

	// TODO @auth methods are only called on authenticated user?
	// TODO provide a method for getting authenticated user

	/**
	 * Credentials are verified upon first authenticated request
	 * @param string $username
	 * @param string $password
	 */
	public function authenticate($username, $password)
	{
		$this->container->get('authenticator')->setCredentials($username, $password);

		// // TODO dbg, remove
		// $this->getAuthenticatedUser();
		// $this->container->get('urlBuilder')->addMap('userId', $this->userId);
	}

	public function dbg_validateCrendentials()
	{
		$this->authenticator->getCookie();
	}

	public function dbg_getUser()
	{
		// return new User($this->container->get('authenticator'), $this->container->get('urlBuilder'));
	}

}
