<?php

namespace Csfd;

use Csfd\Parsers;
use TestCase;


class SearchTest extends TestCase
{

	private $search;


	/**
	 * @covers Csfd\Search::__construct()
	 */
	public function setUp()
	{
		$this->search = new Search(
			$this->getUrlBuilder(),
			$this->getRequestFactory(),
			new Parsers\Search,
			$this->getUsersRepository(),
			$this->getMoviesRepository(),
			$this->getAuthorsRepository()
		);
	}

	/**
	 * @covers Csfd\Search::getResult()
	 */
	public function testGetResult()
	{
		$e = Access($this->search);
		$this->assertInstanceOf('Csfd\Networking\Request', $e->getResult('query'));
	}

	/**
	 * @covers Csfd\Search::findEntity()
	 * @covers Csfd\Search::findUser()
	 * @covers Csfd\Parsers\Search::getIds()
	 * @covers Csfd\Parsers\Search::getUsers()
	 */
	public function testFindUser()
	{
		$ids = $this->resultToIds($this->search->findUser('userDoesNotExist'));
		$this->assertSame([], $ids);

		$ids = $this->resultToIds($this->search->findUser('csfdApiTest'));
		$this->assertSame([460251], $ids);

		$ids = $this->resultToIds($this->search->findUser('spike'));
		$this->assertSame([
				402135, 111737, 75401, 117435, 339139, 92296, 460552,
				286671, 30174, 30000, 2638, 51341, 5062, 113366
			], $ids);
	}


	/**
	 * @covers Csfd\Search::findEntity()
	 * @covers Csfd\Search::findMovie()
	 * @covers Csfd\Parsers\Search::getIds()
	 * @covers Csfd\Parsers\Search::getMovies()
	 */
	public function testFindMovie()
	{
		// empty result
		$ids = $this->resultToIds($this->search->findMovie('movieDoesNotExist'));
		$this->assertSame([], $ids);

		// single result, redirect
		// (intentional inner quotes)
		$ids = $this->resultToIds($this->search->findMovie('"Pelíšky slavných"'));
		$this->assertSame([267763], $ids);

		// both full and short results
		$ids = $this->resultToIds($this->search->findMovie('gatsby'));
		$this->assertSame([
				29491, 19372, 293006, 24915, 26359, 134362
			], $ids);
	}


	/**
	 * @covers Csfd\Search::findEntity()
	 * @covers Csfd\Search::findAuthor()
	 * @covers Csfd\Parsers\Search::getIds()
	 * @covers Csfd\Parsers\Search::getAuthors()
	 */
	public function testFindAuthor()
	{
		// empty result
		$ids = $this->resultToIds($this->search->findAuthor('authorDoesNotExist'));
		$this->assertSame([], $ids);

		// single result, redirect
		$ids = $this->resultToIds($this->search->findAuthor('Chiwetel Ejiofor'));
		$this->assertSame([10572], $ids);

		// both full and short results
		$ids = $this->resultToIds($this->search->findAuthor('Pope'));
		$this->assertSame([
				32165, 65317, 98882, 96675, 4011, 67584,
				88502, 98386, 100647, 72405, 68621, 8814,
			], $ids);
	}


	private function resultToIds(array $entities)
	{
		$ids = [];
		foreach ($entities as $entity)
		{
			$ids[] = $entity->getId();
		}
		return $ids;
	}
}
