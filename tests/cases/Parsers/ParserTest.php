<?php

namespace Csfd\Parsers;

use DateTime;
use TestCase;


class ParserTest extends TestCase
{

	private $parser;

	public function setUp()
	{
		$this->parser = new TestParser;
	}
	
	/** @covers Csfd\Parsers\Parser::match() */
	public function testMatch()
	{
		$e = Access($this->parser);
		$exp = [
			0 => '_d_',
			'char' => 'd',
			1 => 'd',
		];
		$this->assertSame($exp, $e->match('ab_d_p', '~_(?P<char>.)_~'));
	}

	/**
	 * @covers Csfd\Parsers\Parser::getValue()
	 * @expectedException Csfd\InternalException
	 */
	public function testGetValue_groupMissing()
	{
		$e = Access($this->parser);
		$e->getValue('aBa', '~a(?P<wrongName>.)a~');
	}

	/** @covers Csfd\Parsers\Parser::getValue() */
	public function testGetValue()
	{
		$e = Access($this->parser);
		$this->assertSame('B', $e->getValue('aBa', '~a(?P<value>.)a~'));
	}

	/** @covers Csfd\Parsers\Parser::splitByBr() */
	public function testSplitByBr()
	{
		$e = Access($this->parser);
		$this->assertSame(['ab'], $e->splitByBr('ab'));
		$this->assertSame(['a', 'b'], $e->splitByBr('a<br>b'));
		$this->assertSame(['a', 'b'], $e->splitByBr('a<br />b'));
	}

	/** @covers Csfd\Parsers\Parser::getNode() */
	public function testGetNode()
	{
		$e = Access($this->parser);
		$html = file_get_contents(__DIR__ . '/fixtures/test.html');
		$node = $e->getNode($html, '//div/b');
		$this->assertInstanceOf('Symfony\Component\DomCrawler\Crawler', $node);
		$this->assertSame('content', $node->text());
	}

	/**
	 * @covers Csfd\Parsers\Parser::getNode()
	 * @expectedException Csfd\Parsers\Exception
	 * @expectedExceptionCode Csfd\Parsers\Exception::NODE_NOT_FOUND
	 */
	public function testGetNode_notFound()
	{
		$e = Access($this->parser);
		$e->getNode('', '//div/barbara');
	}

	/** @covers Csfd\Parsers\Parser::getFormToken() */
	public function testGetFormToken()
	{
		$e = Access($this->parser);
		$html = file_get_contents(__DIR__ . '/fixtures/form.html');
		$token = $e->getFormToken($html, 'with-token');
		$this->assertSame('tokenValue', $token);
	}

	/**
	 * @covers Csfd\Parsers\Parser::getFormToken()
	 * @expectedException Csfd\Parsers\Exception
	 * @expectedExceptionCode Csfd\Parsers\Exception::FORM_NOT_FOUND
	 */
	public function testGetFormToken_formNotFound()
	{
		$e = Access($this->parser);
		$e->getFormToken('', 'any');
	}

	/**
	 * @covers Csfd\Parsers\Parser::getFormToken()
	 * @expectedException Csfd\Parsers\Exception
	 * @expectedExceptionCode Csfd\Parsers\Exception::TOKEN_NOT_FOUND
	 */
	public function testGetFormToken_tokenNotFound()
	{
		$e = Access($this->parser);
		$html = file_get_contents(__DIR__ . '/fixtures/form.html');
		$e->getFormToken($html, 'without-token');
	}

	/** @covers Csfd\Parsers\Parser::parseCzechDateTime() */
	public function testParseCzechDateTime()
	{
		$e = Access($this->parser);
		$date = $e->parseCzechDateTime('20.12.2013 21:51');
		$this->assertInstanceOf('DateTime', $date);
		$exp = new DateTime('2013-12-20 21:51');
		$this->assertSame($exp->format('c'), $date->format('c'));
	}

	/** @covers Csfd\Parsers\Parser::normalizeUrl() */
	public function testNormalizeUrl()
	{
		$e = Access($this->parser);
		$in = '//img.csfd.cz/files/images/user/avatars/158/155/158155401_a5d9bc.jpg';
		$exp = 'http://img.csfd.cz/files/images/user/avatars/158/155/158155401_a5d9bc.jpg';
		$this->assertSame($exp, $e->normalizeUrl($in));
	}

	/** @covers Csfd\Parsers\Parser::getIdFromUrl() */
	public function testGetIdFromUrl()
	{
		$url = 'http://www.csfd.cz/uzivatel/460251-csfdapitest/?foo=123-bar';
		$this->assertSame(460251, $this->parser->getIdFromUrl($url));

		$url = 'http://www.csfd.cz/film/315329-ctyri-dohody/?foo=123-bar';
		$this->assertSame(315329, $this->parser->getIdFromUrl($url));

		$url = 'http://www.csfd.cz/tvurce/1523-jaroslav-dusek/?foo=123-bar';
		$this->assertSame(1523, $this->parser->getIdFromUrl($url));
	}

	/**
	 * @covers Csfd\Parsers\Parser::getIdFromUrl()
	 * @expectedException Csfd\Parsers\Exception
	 * @expectedExceptionCode Csfd\Parsers\Exception::URL_ID_NOT_FOUND
	 */
	public function testGetIdFromUrl_notFound()
	{
		$this->parser->getIdFromUrl('http://www.csfd.cz/whatever/460251-csfdapitest/');
	}

}

class TestParser extends Parser
{

}
