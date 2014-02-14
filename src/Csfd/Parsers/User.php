<?php

namespace Csfd\Parsers;

use Symfony\Component\DomCrawler\Crawler;


class User extends Parser
{

	/**
	 * @param string $html any page after authentication
	 * @return int id
	 */
	public function getCurrentUserId($html)
	{
		try {
			$anchor = $this->getNode($html, '//*[@id="user-menu"]/a');

		} catch (Exception $e) {
			throw new Exception('Passed html page does not contain expected node with user id.', Exception::USER_NODE_NOT_FOUND, $e);
		}

		return $this->getIdFromUrl($anchor->attr('href'));
	}

	/**
	 * @param $html
	 * @return string html
	 */
	public function getProfile($html)
	{
		return $this->getNode($html, '//*[@class="user-profile"]')->html();
	}

	/**
	 * @param $html
	 * @return string
	 */
	public function getUsername($html)
	{
		return $this->getNode($html, '//*[@class="info"]/h2')->text();
	}

	/**
	 * @param $html
	 * @return array of strings
	 */
	private function getNames($html)
	{
		$fullName = $this->getNode($html, '//*[@class="info"]/h3')->text();
		return explode(' ', $fullName);
	}

	/**
	 * @param $html
	 * @return string
	 */
	public function getFirstName($html)
	{
		return $this->getNames($html)[0];
	}

	/**
	 * @param $html
	 * @return string
	 */
	public function getLastName($html)
	{
		$names = $this->getNames($html);
		return end($names);
	}

	/**
	 * @param $html
	 * @return array of strings
	 */
	private function getAboutNodes($html)
	{
		$text = $this->getNode($html, '//*[@class="info"]/p')->html();
		return $this->splitByBr($text);
	}

	/**
	 * @param $html
	 * @return string
	 */
	public function getLocation($html)
	{
		return trim($this->getAboutNodes($html)[0]);
	}

	/**
	 * @param $html
	 * @return string
	 */
	public function getAbout($html)
	{
		return trim($this->getAboutNodes($html)[1]);
	}

	/**
	 * @param $html
	 * @return array [method => string]
	 */
	public function getContact($html)
	{
		$node = $this->getNode($html, '//*[@class="contact"]');
		$nodes = $this->splitByBr($node->html());
		$contact = [];
		foreach ($nodes as $node)
		{
			if (strpos($node, '">homepage</a>') === FALSE && strpos($node, ':') !== FALSE)
			{
				list($method, $value) = explode(':', $node, 2);
				$method = strToLower(trim($method));
				$contact[$method] = trim(strip_tags($value));
			}
		}
		// TODO process homepage
		// TODO process email
		return $contact;
	}

	/**
	 * @param $html
	 * @return int
	 */
	public function getPoints($html)
	{
		return (int) $this->getNode($html, '//*[@class="points"]')->text();
	}

	private function getActivity($html)
	{
		$text = $this->getNode($html, '//*[@class="activity"]')->html();
		return $this->splitByBr($text);
	}

	/**
	 * @param $html
	 * @return \DateTime
	 */
	public function getRegistered($html)
	{
		$text = $this->getActivity($html)[0];
		$text = substr(trim($text), mb_strlen('Na ČSFD.cz od: '));
		return $this->parseCzechDateTime($text);
	}

	/**
	 * @param $html
	 * @throws Exception
	 * @return \DateTime|NULL|TRUE
	 * TRUE if user is currently online
	 * NULL if information is not available
	 */
	public function getLastActivity($html)
	{
		// @codeCoverageIgnoreStart

		// TODO handle relative czech date (replace dnes with today, strToTime)
		// $text = $this->getActivity($html)[1];
		throw new Exception('not implemented');

		// @codeCoverageIgnoreEnd
	}

	public function getAvatarUrl($html)
	{
		$url = $this->getNode($html, '//img[@class="avatar"]')->attr('src');
		return $this->normalizeUrl($url);
	}

	public function getRatings($html)
	{
		try {
			$nodes = $this->getNodes($html, '//*[@class="profile-content ratings"]//tbody/tr');
		} catch (Exception $e) {
			return []; // user has no ratings
		}

		$ratings = $nodes->each(function(Crawler $row) {
			$id = $this->getIdFromUrl($row->filterXPath('//td[1]/a')->attr('href'));

			$node = $row->filterXPath('//td[2]/img');
			$rating = $node->count() ? strlen($node->attr('alt')) : 0;

			$date = $this->parseCzechDate($row->filterXPath('//td[3]')->text());

			return [$id, $rating, $date];
		});

		return $ratings;
	}

}
