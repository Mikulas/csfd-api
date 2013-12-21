<?php

namespace Csfd\Parsers;

use Symfony\Component\DomCrawler\Crawler;
use Csfd\InternalException;


abstract class Parser
{

	protected function match($subject, $pattern)
	{
		$matches = [];
		preg_match($pattern, $subject, $matches);
		return $matches;
	}

	protected function getValue($subject, $pattern)
	{
		$res = $this->match($subject, $pattern);
		$key = 'value';
		if (!isset($res[$key]))
		{
			throw new InternalException("Pattern `$pattern` does not contain matching group `$key`. Hint: `(?P<$key>)`.");
		}
		return $res[$key];
	}

	protected function getNode($html, $xpath)
	{
		$nodes = new Crawler($html);
		$filtered = $nodes->filterXPath($xpath);
		if ($filtered->count() === 0)
		{
			throw new Exception("Html does not contain `$xpath`.", Exception::NODE_NOT_FOUND);
		}
		return $filtered;
	}
	
	public function getFormToken($html, $formId)
	{
		try {
			$form = $this->getNode($html, '//form[@id="' . $formId . '"]');

		} catch (Exception $e) {
			throw new Exception("Form [id=$formId] not found.", Exception::FORM_NOT_FOUND, $e);
		}

		$tokenField = '_token_';
		$token = $form->filterXPath('//*[@name="' . $tokenField . '"]');

		if (!$token->count())
		{
			throw new Exception("Form [id=$formId] does not contain field ` . $tokenField . `.", Exception::TOKEN_NOT_FOUND);
		}

		return $token->attr('value');
	}

}
