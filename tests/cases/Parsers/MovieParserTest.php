<?php

namespace Csfd\Parsers;

use CachedRequestFactory;
use Csfd\Entities\Author as AuthorE;
use Csfd\Networking\UrlBuilder;
use DateTime;
use TestCase;


class MovieParserTest extends TestCase
{

	/** @see http://www.csfd.cz/film/228329-avatar/ */
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

	/** @covers Csfd\Parsers\Movie::getPosterUrls() */
	public function testGetPosterUrls()
	{
		$urls = $this->parser->getPosterUrls($this->html);
		$this->assertInternalType('array', $urls);
		$this->assertCount(22, $urls);
		foreach ($urls as $url)
		{
			$this->assertTrue((bool) preg_match('~^http://img.csfd.cz/(posters|files)~', $url));
		}
	}

	/** @covers Csfd\Parsers\Movie::getPosterUrl() */
	public function testGetPosterUrl()
	{
		$url = $this->parser->getPosterUrl($this->html);
		$exp = 'http://img.csfd.cz/posters/22/228329_dvd_1.jpg?h180&1';
		$this->assertSame($exp, $url);
	}

	/** @covers Csfd\Parsers\Movie::getGenres() */
	public function testGetGenres()
	{
		$out = $this->parser->getGenres($this->html);
		$exp = ['akční', 'dobrodružný', 'fantasy', 'sci-fi'];
		$this->assertSame($exp, $out);
	}

	/** @covers Csfd\Parsers\Movie::getOrigin() */
	public function testGetOrigin()
	{
		$out = $this->parser->getOrigin($this->html);
		$exp = ['GB', 'US'];
		$this->assertSame($exp, $out);
	}

	/** @covers Csfd\Parsers\Movie::getYear() */
	public function testGetYear()
	{
		$out = $this->parser->getYear($this->html);
		$this->assertInternalType('integer', $out);
		$this->assertSame(2009, $out);
	}

	/** @covers Csfd\Parsers\Movie::getDuration() */
	public function testGetDuration()
	{
		$out = $this->parser->getDuration($this->html);
		$this->assertInternalType('integer', $out);
		$this->assertSame(162, $out);
	}

	/** @covers Csfd\Parsers\Movie::getAuthors() */
	public function testGetAuthors()
	{
		$out = $this->parser->getAuthors($this->html);
		$this->assertArrayHasKey(AuthorE::DIRECTOR, $out);
		$this->assertArrayHasKey(AuthorE::CAMERA, $out);
		$this->assertArrayHasKey(AuthorE::COMPOSER, $out);
		$this->assertArrayHasKey(AuthorE::WRITER, $out);
		$this->assertArrayHasKey(AuthorE::ACTOR, $out);
		foreach ($out as $role => $ids)
		{
			$this->assertInternalType('array', $ids);
			foreach ($ids as $id)
			{
				$this->assertInternalType('int', $id);
			}
		}
	}

	/** @covers Csfd\Parsers\Movie::getPlots() */
	public function testGetPlots()
	{
		$out = $this->parser->getPlots($this->html);
		$this->assertInternalType('array', $out);
		$this->assertCount(3, $out);
		foreach ($out as $node)
		{
			$this->assertInternalType('array', $node);
			$this->assertArrayHasKey('plot', $node);
			$this->assertArrayHasKey('user', $node);
			$this->assertSame($node['plot'], strip_tags($node['plot']), 'Plot should not contain html');
		}
	}

	/**
	 * @covers Csfd\Parsers\Movie::getNames()
	 * @covers Csfd\Parsers\Movie::getCountryCode()
	 */
	public function testGetNames()
	{
		$names = $this->parser->getNames($this->html);
		$exp = [
			'CZ' => 'Avatar',
			'SK' => 'Avatar',
			'US' => 'Avatar',
		];
		$this->assertSame($exp, $names);
	}

	/** @covers Csfd\Parsers\Movie::getOfficialUrl() */
	public function testGetOfficialUrl()
	{
		$url = $this->parser->getOfficialUrl($this->html);
		$this->assertSame('http://www.avatarmovie.com/', $url);
	}

	/** @covers Csfd\Parsers\Movie::getImdbUrl() */
	public function testGetImdbUrl()
	{
		$url = $this->parser->getImdbUrl($this->html);
		$this->assertSame('http://www.imdb.com/title/tt0499549/', $url);
	}

	/** @covers Csfd\Parsers\Movie::getImdbId() */
	public function testGetImdbId()
	{
		$id = $this->parser->getImdbId($this->html);
		$this->assertSame('tt0499549', $id);
	}

	/** @covers Csfd\Parsers\Movie::getRelatedMovies() */
	public function testGetRelatedMovies()
	{
		$id = $this->parser->getRelatedMovies($this->html);
		$this->assertSame([328911, 286594, 277495, 343401, 238081], $id);
	}

	/** @covers Csfd\Parsers\Movie::getSimilarMovies() */
	public function testGetSimilarMovies()
	{
		$id = $this->parser->getSimilarMovies($this->html);
		$this->assertSame([343401, 238081], $id);
	}

	/** @covers Csfd\Parsers\Movie::getTags() */
	public function testGetTags()
	{
		$id = $this->parser->getTags($this->html);
		$this->assertSame(['mimozemšťani', 'vesmír', 'zbraně', '3D', 'bitva', 'vojenská vzpoura'], $id);
	}

}
