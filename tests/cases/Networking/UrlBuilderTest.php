<?php

namespace Csfd\Networking;

use TestCase;


class UrlBuilderTest extends TestCase
{

	/**
	 * @covers Csfd\Networking\UrlBuilder::factory()
	 * @expectedException Csfd\InternalException
	 */
	public function testConfigNotFound()
	{
		$config = __DIR__ . '/doesNotExist';
		$this->assertInstanceOf('Csfd\Networking\UrlBuilder', UrlBuilder::factory($config));
	}

	private function getUrlsFile()
	{
		return __DIR__ . '/urls.yml';
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
		$this->assertSame('_root_url_', $builder->getRoot());
	}

}
