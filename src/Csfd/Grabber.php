<?php

namespace Csfd;

use cURL;
use Sunra\PhpSimple\HtmlDomParser;


class Grabber
{

	const GET = 'GET';
	const POST = 'POST';

	const MAX_REDIRECTS = 10;
	const TIMEOUT = 2;

	const USER_AGENT = 'CSFDAPI Grabber 1.2.1 (csfdapi.cz) mikulas@dite.pro';


	/** @var string */
	protected $url;

	/** @var cURL\Queue */
	private $queue;

	/** @var array of mixed */
	private $responses;


	public function __construct($url = NULL)
	{
		$this->url = $url ? $url : 'http://www.csfd.cz/';
	}



	public function request($http, $method, $args = [])
	{
		$query = http_build_query($args);

		$context = stream_context_create([
			'http' => [
				'method'  => $http,
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $http === self::POST ? $query : NULL,
				'follow_location' => TRUE,
				'max_redirects' => self::MAX_REDIRECTS,
				'timeout' => self::TIMEOUT,
			]
		]);

		$url = $this->url . $method . ($query && $http === self::GET ? '?' . $query : '');
		$result = @file_get_contents($url, FALSE, $context); // @ - throw exception
		if (!$result) {
			throw new \RuntimeException('Invalid response received.');
		}

		return HtmlDomParser::str_get_html($result);
	}



	public function queueInit()
	{
		$this->queue = new cURL\RequestsQueue;
		$this->queue->getDefaultOptions()
			->set(\CURLOPT_CONNECTTIMEOUT, self::TIMEOUT)
			->set(\CURLOPT_TIMEOUT, self::TIMEOUT)
			->set(\CURLOPT_FOLLOWLOCATION, TRUE)
			->set(\CURLOPT_MAXREDIRS, self::MAX_REDIRECTS)
			->set(\CURLOPT_USERAGENT, self::USER_AGENT)
			->set(\CURLOPT_RETURNTRANSFER, TRUE);

		$this->queue->addListener('complete', function (cURL\Event $event) {
			$result = $event->response->getContent();
			if (!$result) {
				throw new \RuntimeException('Invalid response received.');
			}
			$this->responses[] = HtmlDomParser::str_get_html($result);
		});
	}



	public function enqueue($url)
	{
		$this->queue->attach(new cURL\Request("{$this->url}$url"));
	}



	public function queueRun()
	{
		$this->queue->send();
		return $this->responses;
	}

}
