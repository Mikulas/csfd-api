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

	/** @var Repositories\Movies */
	public $movies;

	/** @var Repositories\Authors */
	public $authors;

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

		foreach (['users', 'movies', 'authors'] as $repo)
		{
			$this->$repo = $this->container->get("repo.$repo");
			$this->$repo->setContainer($this);
		}
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
