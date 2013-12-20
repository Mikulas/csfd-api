<?php

namespace Csfd\Parsers;


class User extends Parser
{

	/**
	 * @param string $html any page after authentication
	 * @return int id
	 */
	public function getCurrentUserId($html)
	{
		$anchor = $this->getNode($html, '//*[@id="user-menu"]/a');
		if (!$anchor->count())
		{
			throw new Exception('Passed html page does not contain expected node with user id.');
		}

		return $this->getIdFromUrl($anchor->attr('href'));
	}

	/**
	 * @param string $url (e.g. /uzivatel/268216-mikulasdite/)
	 * @return int id (e.g. 268216)
	 */
	public function getIdFromUrl($url)
	{
		return (int) $this->getValue($url, '~/(?P<value>\d+)-~');
	}
	
}
