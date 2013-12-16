<?php

namespace Csfd\Parsers;


class User extends Parser
{

	/**
	 * @param string $url (e.g. /uzivatel/268216-mikulasdite/)
	 * @return int id (e.g. 268216)
	 */
	public function getIdFromUrl($url)
	{
		return (int) $this->getValue($url, '~/(?P<value>\d+)-~');
	}
	
}
