<?php

namespace Csfd\Parsers;

use Csfd\Parsers\Exception;
use Csfd\InternalException;


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

}
