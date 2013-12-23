<?php

use Csfd\Networking\UrlBuilder;
use Csfd\Networking\RequestFactory;
use Csfd\Repositories;
use Csfd\Authentication\Authenticator;
use Csfd\Parsers;
use Symfony\Component\Yaml\Yaml;


class TestCase extends PHPUnit_Framework_TestCase
{

	private static $singletons;

	/**
	 * Vynucuje existenci @covers anotace při generování code coverage.
	 *
	 * @author Jan Tvrdík
	 * @return mixed
	 */
	protected function runTest()
	{
		$annotations = $this->getAnnotations();
		if (
			!isset($annotations['class']['covers']) && !isset($annotations['method']['covers'])
			&& !isset($annotations['class']['coversNothing']) && !isset($annotations['method']['coversNothing'])
		)
		{
			$this->markTestIncomplete('Missing mandatory @covers or @coversNothing annotation');
		}
		return parent::runTest();
	}

	protected function getConfig()
	{
		$config = Yaml::parse(file_get_contents(__DIR__ . '/config.yml'));

		$localFile = __DIR__ . '/config.local.yml';
		if (!is_file($localFile))
		{
			$this->markTestIncomplete('Configuration file config.local.yml not found.');
		}

		$local = Yaml::parse(file_get_contents($localFile));
		$config = array_replace_recursive($config, $local);

		return $config;
	}


	/** SERVICES */

	private function getSingleton($name, $callback)
	{
		if (!isset(self::$singletons[$name]))
		{
			self::$singletons[$name] = $callback();
		}
		return self::$singletons[$name];
	}

	protected function getUrlBuilder()
	{
		return $this->getSingleton(__METHOD__, function() {
			return UrlBuilder::factory(__DIR__ . '/../src/Csfd/urls.yml');
		});
	}

	protected function getMockUrlBuilder()
	{
		return $this->getSingleton(__METHOD__, function() {
			return new MockUrlBuilder;
		});
	}

	protected function getRequestFactory()
	{
		return $this->getSingleton(__METHOD__, function() {
			$requestFactory = new CachedRequestFactory;
			$requestFactory->setRequestClass('Csfd\Networking\Request');
			return $requestFactory;
		});
	}

	protected function getMockRequestFactory()
	{
		return $this->getSingleton(__METHOD__, function() {
			$requestFactory = new RequestFactory;
			$requestFactory->setRequestClass('MockRequest');
			return $requestFactory;
		});
	}
	
	protected function getAuthenticator()
	{
		return $this->getSingleton(__METHOD__, function() {
			return new Authenticator(
				$this->getUrlBuilder(),
				new Parsers\User,
				new Parsers\Authentication,
				$this->getRequestFactory()
			);
		});
	}

	protected function getMockAuthenticator()
	{
		return $this->getSingleton(__METHOD__, function() {
			return new MockAuthenticator;
		});
	}

	protected function getUsersRepository()
	{
		return $this->getSingleton(__METHOD__, function() {
			$repo = new Repositories\Users(
				$this->getMockAuthenticator(),
				$this->getMockUrlBuilder(),
				$this->getMockRequestFactory()
			);
			$repo->setParserClass('Csfd\Parsers\User');
			$repo->setEntityClass('Csfd\Entities\User');
			return $repo;
		});
	}

	protected function getMoviesRepository()
	{
		return $this->getSingleton(__METHOD__, function() {
			$repo = new Repositories\Movies(
				$this->getMockAuthenticator(),
				$this->getMockUrlBuilder(),
				$this->getMockRequestFactory()
			);
			$repo->setParserClass('Csfd\Parsers\Movie');
			$repo->setEntityClass('Csfd\Entities\Movie');
			return $repo;
		});
	}

	protected function getAuthorsRepository()
	{
		return $this->getSingleton(__METHOD__, function() {
			$repo = new Repositories\Authors(
				$this->getMockAuthenticator(),
				$this->getMockUrlBuilder(),
				$this->getMockRequestFactory()
			);
			$repo->setParserClass('Csfd\Parsers\Author');
			$repo->setEntityClass('Csfd\Entities\Author');
			return $repo;
		});
	}

}
