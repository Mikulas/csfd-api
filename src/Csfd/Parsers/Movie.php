<?php

namespace Csfd\Parsers;

use Csfd\InternalException;
use Csfd\Parsers\Exception;
use Symfony\Component\DomCrawler\Crawler;


class Movie extends Parser
{

	/** @return int 0..100 */
	public function getRating($html)
	{
		return (int) $this->getNode($html, '//h2[@class="average"]')->text();
	}

	/** @return int|NULL if not in top 100 */
	public function getChartPosition($html)
	{
		return (int) $this->getNode($html, '//p[@class="charts"]/a')->text();
	}

	/** @return string url */
	public function getPosterUrl($html)
	{
		$url = $this->getNode($html, '//img[@class="film-poster"]')->attr('src');
		return $this->normalizeUrl($url);
	}

	/**
	 * @return string short ISO 639-1 code
	 */
	private function getLanguage($text)
	{
		$text = strToLower(trim($text));
		switch ($text)
		{
			case 'usa': return 'en';
			case 'sk název': return 'sk';
			default:
				throw new Exception("Language `$text` not recognized.");
		}
	}

	/** @return array strings */
	public function getNames($html)
	{
		$cs = $this->getNode($html, '//div[@class="content"]//h1')->text();
		$names['cs'] = trim($cs);

		$nodes = $this->getNode($html, '//ul[@class="names"]/li');
		$nodes->each(function(Crawler $node, $i) use (&$names) {
			$langAlt = $node->filterXPath('//img')->attr('alt');
			$lang = $this->getLanguage($langAlt);

			$name = trim($node->filterXPath('//h3')->text());
			$names[$lang] = $name;
		});

		ksort($names);
		return $names;
	}

}
