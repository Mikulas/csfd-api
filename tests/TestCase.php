<?php

use Csfd\Networking\UrlBuilder;
use Csfd\Networking\RequestFactory;


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
	
	protected function getMockAuthenticator()
	{
		return $this->getSingleton(__METHOD__, function() {
			return new MockAuthenticator;
		});
	}

}
