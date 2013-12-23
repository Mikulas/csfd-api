<?php

namespace Csfd\Networking;

use Csfd\Entities\Movie;
use Csfd\Parsers;
use TestCase;


class UrlAccessTest extends TestCase
{

	/**
	 * @covers Csfd\Networking\UrlAccess::getUrl()
	 * @expectedException Csfd\InternalException
	 */
	public function testUnsetClass()
	{
		$foo = Access(new UrlAccessImplement);
		$foo->getUrl('key');
	}

	/** @covers Csfd\Networking\UrlAccess::setUrlBuilder() */
	public function testSetUrlBuilder()
	{
		$e = Access(new UrlAccessImplement);
		$builder = $this->getMockUrlBuilder();
		$e->setUrlBuilder($builder);
		$this->assertSame($builder, $e->urlBuilder);
	}

	/** @covers Csfd\Networking\UrlAccess::getUrl() */
	public function testGetUrl()
	{
		$foo = Access(new UrlAccessImplement);
		$foo->setUrlBuilder($this->getMockUrlBuilder());
		$this->assertSame('foo:bar:key', $foo->getUrl('key'));
	}

	/**
	 * @covers Csfd\Networking\UrlAccess::getConfigKeys()
	 */
	public function testGetConfigKeys()
	{
		$entity = new Movie(
			$this->getMockAuthenticator(),
			$this->getMockUrlBuilder(),
			new Parsers\Movie,
			$this->getMockRequestFactory(),
			1
		);
		$e = Access($entity);
		$this->assertSame(['entities', 'movie'], $e->getConfigKeys());
	}

}

class UrlAccessImplement
{
	use UrlAccess;

	public function getConfigKeys()
	{
		return ['foo', 'bar'];
	}
}
