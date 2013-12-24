<?php

namespace Csfd\Parsers;

use CachedRequestFactory;
use Csfd\Networking\UrlBuilder;
use DateTime;
use TestCase;


class MovieParserTest extends TestCase
{

	const ID = 228329;

	private $parser;
	private $html;

	public function setUp()
	{
		$this->parser = new Movie;

		$builder = UrlBuilder::factory(__DIR__ . '/../../../src/Csfd/urls.yml');
		$url = $builder->get(['entities', 'movie', 'default'], ['entityId' => self::ID]);

		$factory = $this->getRequestFactory();
		$this->html = $factory->create($url)->getContent();
	}

	/** @covers Csfd\Parsers\Movie::getRating() */
	public function testGetRating()
	{
		$rating = $this->parser->getRating($this->html);
		$this->assertInternalType('integer', $rating);
		$this->assertGreaterThanOrEqual(0, $rating);
		$this->assertLessThanOrEqual(100, $rating);
	}

	/** @covers Csfd\Parsers\Movie::getChartPosition() */
	public function testGetChartPosition()
	{
		$rating = $this->parser->getChartPosition($this->html);
		// TODO test NULL
		$this->assertInternalType('integer', $rating);
		$this->assertGreaterThanOrEqual(1, $rating);
		$this->assertLessThanOrEqual(250, $rating); // not sure what maximum is
	}

	/** @covers Csfd\Parsers\Movie::getPosterUrl() */
	public function testGetPosterUrl()
	{
		$url = $this->parser->getPosterUrl($this->html);
		$exp = 'http://img.csfd.cz/posters/22/228329_dvd_1.jpg?h180';
		$this->assertSame($exp, $url);
	}

}
