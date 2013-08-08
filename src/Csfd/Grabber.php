<?php

namespace Csfd;

use cURL;
use Sunra\PhpSimple\HtmlDomParser;


class Grabber
{

	const GET = 'GET';
	const POST = 'POST';


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
			]
		]);

		$url = $this->url . $method . ($http === self::GET ? '?' . $query : '');
		$result = file_get_contents($url, FALSE, $context);

		if (!$result) {
			throw new \RuntimeException('Invalid response received.');
		}

		return HtmlDomParser::str_get_html($result);
	}



	public function queueInit()
	{
		$this->queue = new cURL\RequestsQueue;
		$this->queue->getDefaultOptions()
			->set(\CURLOPT_TIMEOUT, 5)
			->set(\CURLOPT_FOLLOWLOCATION, TRUE)
			->set(\CURLOPT_RETURNTRANSFER, TRUE);

		$this->queue->addListener('complete', function (cURL\Event $event) {
			$this->responses[] = HtmlDomParser::str_get_html($event->response->getContent());
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
