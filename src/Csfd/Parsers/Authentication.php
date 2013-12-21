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
		try {
			$errors = $this->getNode($html, '//*[@class="errors"]/ul/li');

		} catch (Exception $e) {
			return FALSE; // errors not found
		}
		return TRUE; // errors found
	}
	
}
