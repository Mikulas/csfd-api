<?php

namespace Csfd;

use Csfd\Networking\UrlAccess;
use Csfd\Networking\UrlBuilder;
use Csfd\Networking\RequestFactory;
use Csfd\Parsers;
use Csfd\Repositories\Authors;
use Csfd\Repositories\Users;
use Csfd\Repositories\Movies;


class Search
{

	use UrlAccess;

	private $requestFactory;
	private $parser;
	private $users;
	private $movies;
	private $authors;

	public function __construct(UrlBuilder $urlBuilder, RequestFactory $requestFactory,
		Parsers\Search $parser, Users $users, Movies $movies, Authors $authors)
	{
		$this->setUrlBuilder($urlBuilder);
		$this->requestFactory = $requestFactory;
		$this->parser = $parser;
		$this->users = $users;
		$this->movies = $movies;
		$this->authors = $authors;
	}

	protected function getResult($query)
	{
		$url = $this->getUrl('default', ['query' => urlencode($query)]);
		return $this->requestFactory->create($url);
	}

	private function findEntity($query, $urlMatch, $repo, $parserMethod)
	{
		$result = $this->getResult($query);

		// single search result, csfd redirected to user page
		if (strpos($result->getRedirectUrl(), $urlMatch) !== FALSE)
		{
			$id = $this->parser->getIdFromUrl($result->getRedirectUrl());
			return [$repo->get($id)];
		}

		// ambiguous results, list of entities
		$data = call_user_func([$this->parser, $parserMethod], $result->getContent());
		$entities = [];
		foreach ($data as $data)
		{
			$entities[] = $repo->get($data['id']);
		}
		return $entities;
	}

	public function findUser($query)
	{
		return $this->findEntity($query, '/uzivatel/', $this->users, 'getUsers');
	}

	public function findMovie($query)
	{
		return $this->findEntity($query, '/film/', $this->movies, 'getMovies');
	}

	public function findAuthor($query)
	{
		return $this->findEntity($query, '/tvurce/', $this->authors, 'getAuthors');
	}
	
}
