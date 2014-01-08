<?php

namespace Csfd\Networking;

use TestCase;


class UrlBuilderTest extends TestCase
{

	/**
	 * @covers Csfd\Networking\UrlBuilder::factory()
	 * @expectedException Csfd\InternalException
	 */
	public function testFactory_configNotFound()
	{
		$config = __DIR__ . '/doesNotExist';
		$this->assertInstanceOf('Csfd\Networking\UrlBuilder', UrlBuilder::factory($config));
	}

	public function getInvalidConfigFiles()
	{
		return [
			[__DIR__ . '/fixtures/invalid-1.yml'],
			[__DIR__ . '/fixtures/invalid-2.yml']
		];
	}

	/**
	 * @covers Csfd\Networking\UrlBuilder::factory()
	 * @dataProvider getInvalidConfigFiles
	 * @expectedException Csfd\InternalException
	 */
	public function testFactory_invalidConfig($configFile)
	{
		UrlBuilder::factory($configFile);
	}

	private function getUrlsFile()
	{
		return __DIR__ . '/fixtures/urls.yml';
	}

	/**
	 * @covers Csfd\Networking\UrlBuilder::factory()
	 * @covers Csfd\Networking\UrlBuilder::__construct()
	 */
	public function testFactory()
	{
		$builder = UrlBuilder::factory($this->getUrlsFile());
		$this->assertInstanceOf('Csfd\Networking\UrlBuilder', $builder);
	}

	/**
	 * @covers Csfd\Networking\UrlBuilder::validate()
	 */
	public function testValidate()
	{
		$e = Access($this->getUrlBuilder());
		$this->assertFalse($e->validate([
		]));
		$this->assertFalse($e->validate([
			'root' => []
		]));
		$this->assertTrue($e->validate([
			'root' => 'http://localhost'
		]));
	}

	/**
	 * @covers Csfd\Networking\UrlBuilder::getRoot()
	 */
	public function testGetRoot()
	{
		$builder = UrlBuilder::factory($this->getUrlsFile());
		$this->assertSame('rootUrl/', $builder->getRoot());
	}

	/**
	 * @covers Csfd\Networking\UrlBuilder::get()
	 * @expectedException Csfd\InternalException
	 */
	public function testGet_notFound()
	{
		$builder = UrlBuilder::factory($this->getUrlsFile());
		$builder->get(['invalid']);
	}

	/** @covers Csfd\Networking\UrlBuilder::get() */
	public function testGet()
	{
		$builder = UrlBuilder::factory($this->getUrlsFile());
		$this->assertSame('rootUrl/fooBar', $builder->get(['foo', 'bar']));
	}

	/**
	 * @covers Csfd\Networking\UrlBuilder::getMap()
	 * @covers Csfd\Networking\UrlBuilder::replacePlaceholders()
	 */
	public function testReplacePlaceholders()
	{
		$builder = UrlBuilder::factory($this->getUrlsFile());
		$builder->getMap()->insert('arg1', 'b');
		$this->assertSame('rootUrl/abcde', $builder->get(['foo', 'qaz'], ['arg2' => 'd']));
	}

	/**
	 * @covers Csfd\Networking\UrlBuilder::replacePlaceholders()
	 * @expectedException Csfd\InternalException
	 */
	public function testReplacePlaceholders_unsetMap()
	{
		$builder = UrlBuilder::factory($this->getUrlsFile());
		$builder->get(['foo', 'qaz']);
	}

}
