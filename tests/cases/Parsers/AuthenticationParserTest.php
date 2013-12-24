<?php

namespace Csfd\Parsers;

use CachedRequestFactory;
use Csfd\Networking\UrlBuilder;
use TestCase;


class AuthenticationParserTest extends TestCase
{

	private $parser;

	public function setUp()
	{
		$this->parser = new Authentication;
	}

	/** @covers Csfd\Parsers\Authentication::containsError() */
	public function testContainsError_yes()
	{
		$html = file_get_contents(__DIR__ . '/fixtures/login-error.html');
		$this->assertTrue($this->parser->containsError($html));
	}

	/** @covers Csfd\Parsers\Authentication::containsError() */
	public function testContainsError_no()
	{
		$html = file_get_contents(__DIR__ . '/fixtures/login-success.html');
		$this->assertFalse($this->parser->containsError($html));
	}

}
