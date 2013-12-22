<?php

namespace Csfd;

use Csfd\Configuration\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class Csfd
{

	/** @var ContainerBuilder */
	private $container;

	/** @var Repositories\Users */
	public $users;

	public static function create()
	{
		$container = new ContainerBuilder();
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
		$loader->load('services.yml');
		$container->setParameter('root', __DIR__);
		$container->compile();
		return new static($container);
	}

	public function __construct(ContainerBuilder $container)
	{
		$this->container = $container;

		$this->users = $this->container->get('repo.users');
	}

	/**
	 * Credentials are verified upon first authenticated request
	 * @param string $username
	 * @param string $password
	 */
	public function authenticate($username, $password)
	{
		$this->container->get('authenticator')->setCredentials($username, $password);
	}

}
