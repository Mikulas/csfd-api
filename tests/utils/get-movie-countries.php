<?php

use Csfd\Parsers\Exception;
use Symfony\Component\DomCrawler\Crawler;

//
// WARNING: run with caution, running with 20 requests
// per language gets you blacklisted for a day
//

$loader = require __DIR__ . '/../../vendor/autoload.php';
$loader->add('', __DIR__ . '/../mocks');

CachedRequestFactory::$tempDir = __DIR__ . '/../temp';

$parser = new Csfd\Parsers\Movie;

class SlowRequest extends Csfd\Networking\Request
{

	public function __construct($url, array $args = NULL, $method = self::GET, $cookie = NULL)
	{
		usleep(500);
		return parent::__construct($url, $args, $method, $cookie);
	}

}

$ids = explode("\n", file_get_contents(__DIR__ . '/ids.txt'));

$factory = new CachedRequestFactory;
$factory->setRequestClass('SlowRequest');

foreach ($ids as $cid)
{
	if (!$cid) continue;
	$url = 'http://www.csfd.cz/podrobne-vyhledavani/?origin%5Binclude%5D%5B0%5D=' . $cid . '&ok=Hledat&_form_=film';
	echo "$url\n";
	$html = $factory->create($url)->getContent();
	$dom = new Crawler($html);
	echo "\r                     \r\ntesting language #$cid\n";
	$dom->filterXPath('//td[@class="name"]/a')->each(function(Crawler $node, $i) use ($factory, $parser) {
		if ($i > 5) return;
		$urlMovie = 'http://www.csfd.cz' . $node->attr('href');
		$htmlMovie = $factory->create($urlMovie)->getContent();
		try {
			$parser->getNames($htmlMovie);

		} catch (Exception $e) {
			if ($e->getCode() === Exception::UNKNOWN_COUNTRY)
			{
				echo "\r                         \r" . $e->getMessage() . "\n";
				return;
			}
		}
		echo "\r                      \r$i ok ";
	});
}
