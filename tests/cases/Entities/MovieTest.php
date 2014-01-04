<?php

namespace Csfd\Entities;

use Csfd\Parsers;
use TestCase;


class MovieTest extends TestCase
{

	const ID = 228329;

	private $entity;


	public function setUp()
	{
		$this->entity = new Movie(
			$this->getMockAuthenticator(),
			$this->getUrlBuilder(),
			new Parsers\Movie,
			$this->getRequestFactory(),
			self::ID
		);
		$this->entity->setRepository($this->getMoviesRepository());
		$this->getMockContainer(); // attaches everything
	}

	/** @covers Csfd\Entities\Movie::_getPlots() */
	public function testGetPlots()
	{
		$plots = $this->entity->getPlots();
		foreach ($plots as $plot)
		{
			if ($plot['user'] === NULL)
				continue;

			$this->assertInstanceOf('Csfd\Entities\User', $plot['user']);
		}
	}

}
