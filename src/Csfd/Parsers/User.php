<?php

namespace Csfd\Parsers;

use Csfd\InternalException;


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
			throw new Exception('Passed html page does not contain expected node with user id.', Exception::USER_NODE_NOT_FOUND);
		}

		return $this->getIdFromUrl($anchor->attr('href'));
	}

	/**
	 * @param string $url (e.g. /uzivatel/268216-mikulasdite/)
	 * @return int id (e.g. 268216)
	 */
	public function getIdFromUrl($url)
	{
		try {
			return (int) $this->getValue($url, '~/(?P<value>\d+)-~');

		} catch (InternalException $e) {
			throw new Exception("Url `$url` does not contain user id.", Exception::USER_ID_NOT_FOUND);
		}
	}
	
}
