<?php

namespace Csfd\Parsers;

use Csfd\InternalException;
use DateTime;
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
		$res = $this->match($subject, $pattern);
		$key = 'value';
		if (!isset($res[$key]))
		{
			throw new InternalException("Pattern `$pattern` does not contain matching group `$key`. Hint: `(?P<$key>)`.");
		}
		return $res[$key];
	}

	/**
	 * @param $text
	 * @return array of strings
	 */
	protected function splitByBr($text)
	{
		return preg_split('~\s*<br\s*/?>\s*~i', $text);
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

	/** alias */
	protected function getNodes($html, $xpath)
	{
		return $this->getNode($html, $xpath);
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

	protected function parseCzechDateTime($string)
	{
		return DateTime::createFromFormat('j.n.Y*H:i', $string);
	}

	protected function parseCzechDate($string)
	{
		$date = DateTime::createFromFormat('j.n.Y', $string);
		$date->setTime(0, 0, 0); // not current time
		return $date;
	}

	/**
	 * @param string $css
	 * @return string url
	 */
	protected function getCssUrl($css)
	{
		$url = $this->getValue($css, '~url\([\'"]?(?P<value>[^)]*?)[\'"]?\)~');
		return $this->normalizeUrl(stripslashes($url));
	}

	/**
	 * @param string $url
	 * @return string url
	 */
	protected function normalizeUrl($url)
	{
		$url = preg_replace('~^//~', 'http://', $url);
		return $url;
	}

	/**
	 * @param string $url (e.g. /uzivatel/268216-mikulasdite/)
	 * @return int id (e.g. 268216)
	 */
	public function getIdFromUrl($url)
	{
		try {
			return (int) $this->getValue($url, '~(uzivatel|film|tvurce)/(?P<value>\d+)-~');

		} catch (InternalException $e) {
			throw new Exception("Url `$url` does not contain user id.", Exception::URL_ID_NOT_FOUND);
		}
	}

}
