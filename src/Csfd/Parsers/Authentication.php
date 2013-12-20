<?php

namespace Csfd\Parsers;


class Authentication extends Parser
{

	/**
	 * @param string $html find errors
	 * @return bool
	 */
	public function containsError($html)
	{
		$errors = $this->getNode($html, '//*[@class="errors"]/ul/li');

		return $errors->count();
	}
	
}
