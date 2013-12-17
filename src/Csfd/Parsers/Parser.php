<?php

namespace Csfd\Parsers;

use Symfony\Component\DomCrawler\Crawler;


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
		return $this->match($subject, $pattern)['value'];
	}

	protected function getNode($html, $xpath)
	{
		$nodes = new Crawler($html);
		return $nodes->filterXPath($xpath);
	}
	
	public function getFormToken($html, $formId)
	{
		return $this->getNode($html, '//form[@id="' . $formId . '"]//*[@name="_token_"]')->attr('value');
	}

}
