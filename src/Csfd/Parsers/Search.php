<?php

namespace Csfd\Parsers;


use Symfony\Component\DomCrawler\Crawler;


class Search extends Parser
{

	private function getIds($html, $containerClass)
	{
		try {
			$nodes = $this->getNode($html, '//div[@id="' . $containerClass . '"]//li');

		} catch (Exception $e) {
			return [];
		}

		return $nodes->each(function(Crawler $node, $i) {
			$data = [];

			$anchor = $node->filterXPath('//a')->first();
			$data['id'] = $this->getIdFromUrl($anchor->attr('href'));

			// TODO provide other data so less requests are required

			return $data;
		});
	}

	public function getUsers($html)
	{
		return $this->getIds($html, 'search-users');
	}

	public function getMovies($html)
	{
		return $this->getIds($html, 'search-films');
	}

	public function getAuthors($html)
	{
		return $this->getIds($html, 'search-creators');
	}

}
