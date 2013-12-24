<?php

namespace Csfd\Parsers;

use Csfd\InternalException;
use Csfd\Parsers\Exception;
use Symfony\Component\DomCrawler\Crawler;


class Movie extends Parser
{

	/** @return int 0..100 */
	public function getRating($html)
	{
		return (int) $this->getNode($html, '//h2[@class="average"]')->text();
	}

	/** @return int|NULL if not in top 100 */
	public function getChartPosition($html)
	{
		return (int) $this->getNode($html, '//p[@class="charts"]/a')->text();
	}

	/** @return string url */
	public function getPosterUrl($html)
	{
		$url = $this->getNode($html, '//img[@class="film-poster"]')->attr('src');
		return $this->normalizeUrl($url);
	}

	/**
	 * @return string ISO 3166-1 alpha-2 code
	 * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements
	 */
	private function getCountryCode($text)
	{
		$text = mb_strToLower(trim($text), 'UTF-8');
		$codes = [
			'originální název' => '_', // TODO constant

			'afgánistán' => 'AF',
			'albánie' => 'AL',
			'alžírsko' => 'DZ',
			'argentina' => 'AR',
			'arménie' => 'AM',
			'austrálie' => 'AU',
			'bahamy' => 'BS',
			'bangladéš' => 'BD',
			'barma' => 'MM',
			'belgie' => 'BE',
			'bhután' => 'BT',
			'bolívie' => 'BO',
			'bosna a hercegovina' => 'BA',
			'brazílie' => 'BR',
			'bulharsko' => 'BG',
			'burkina faso' => 'BF',
			'bělorusko' => 'BY',
			'chile' => 'CL',
			'chorvatsko' => 'HR',
			'cz název' => 'CZ',
			'dominik. republika' => 'DO',
			'dánsko' => 'DK',
			'egypt' => 'EG',
			'ekvádor' => 'EC',
			'en název' => 'US',
			'estonsko' => 'EE',
			'etiopie' => 'ET',
			'fed. rep. jugoslávie' => 'HR', // historical
			'filipíny' => 'PH',
			'finsko' => 'FI',
			'francie' => 'FR',
			'gruzie' => 'GE',
			'guatemala' => 'GT',
			'honduras' => 'HN',
			'hong kong' => 'HK',
			'indie' => 'IN',
			'indonésie' => 'ID',
			'irsko' => 'IE',
			'irák' => 'IQ',
			'irán' => 'IR',
			'island' => 'IS',
			'itálie' => 'IT',
			'izrael' => 'IL',
			'jamajka' => 'JM',
			'japonsko' => 'JP',
			'jižní afrika' => 'ZA',
			'jižní korea' => 'KR',
			'jordánsko' => 'JO',
			'jugoslávie' => 'HR', // historical
			'kambodža' => 'KH',
			'kamerun' => 'CM',
			'kanada' => 'CA',
			'katar' => 'QA',
			'kazachstán' => 'KZ',
			'keňa' => 'KE',
			'kolumbie' => 'CO',
			'kongo' => 'CG',
			'korea' => 'KR', // ambiguous
			'kosovo' => 'XK', // temporary
			'kostarika' => 'CR',
			'kuba' => 'CU',
			'kuvajt' => 'KW',
			'kyrgyzstán' => 'KG',
			'libanon' => 'LB',
			'lichtenštejnsko' => 'LI',
			'litva' => 'LT',
			'lotyšsko' => 'LV',
			'makedonie' => 'MK',
			'malajsie' => 'MY',
			'mali' => 'ML',
			'malta' => 'MT',
			'maroko' => 'MA',
			'mauretánie' => 'MR',
			'maďarsko' => 'HU',
			'mexiko' => 'MX',
			'moldavsko' => 'MD',
			'monako' => 'MC',
			'mongolsko' => 'MN',
			'mosambik' => 'MZ',
			'nigérie' => 'NE',
			'nikaragua' => 'NI',
			'nizozemsko' => 'NL',
			'norsko' => 'NO',
			'nový zéland' => 'NZ',
			'německo' => 'DE',
			'palestina' => 'PS',
			'panama' => 'PA',
			'paraguay' => 'PY',
			'peru' => 'PE',
			'pobřeží slonoviny' => 'CI',
			'polsko' => 'PL',
			'portoriko' => 'PR',
			'portugalsko' => 'PT',
			'pákistán' => 'PK',
			'rakousko' => 'AT',
			'rakousko-uhersko' => 'AT', // historical
			'rumunsko' => 'RO',
			'rusko' => 'RU',
			'rwanda' => 'RW',
			'saudská arábie' => 'SA',
			'senegal' => 'SN',
			'severní korea' => 'KP',
			'singapur' => 'SG',
			'sk název' => 'SK',
			'slovensko' => 'SK',
			'slovinsko' => 'SI',
			'sovětský svaz' => 'SU',
			'spojené arabské emiráty' => 'AE',
			'srbsko a černá hora' => 'ME',
			'srbsko' => 'RS',
			'srí lanka' => 'LK',
			'sýrie' => 'SY',
			'tanzánie' => 'TZ',
			'tchaj-wan' => 'TW',
			'thajsko' => 'TH',
			'tibet' => 'TI', // suggested code, not in ISO
			'tunisko' => 'TN',
			'turecko' => 'TR',
			'turkmenistan' => 'TM',
			'ukrajina' => 'UA',
			'uruguay' => 'UY',
			'usa' => 'US',
			'uzbekistán' => 'UZ',
			'vatikán' => 'VA',
			'velká británie' => 'GB',
			'venezuela' => 'VE',
			'vietnam' => 'VN',
			'východní německo' => 'DD',
			'zimbabwe' => 'ZW',
			'západní německo' => 'DE',
			'ázerbajdžán' => 'AZ',
			'írák' => 'IQ',
			'írán' => 'IR',
			'čad' => 'TD',
			'česko' => 'CZ',
			'čína' => 'CN',
			'řecko' => 'GR',
			'španělsko' => 'ES',
			'švédsko' => 'SE',
			'švýcarsko' => 'CH',
		];
		if (!isset($codes[$text]))
		{
				throw new Exception("Contry `$text` not recognized.", Exception::UNKNOWN_COUNTRY);
		}
		return $codes[$text];
	}

	/** @return array strings */
	public function getNames($html)
	{
		$cs = $this->getNode($html, '//div[@class="content"]//h1')->text();
		$names['CZ'] = trim($cs);

		$nodes = $this->getNode($html, '//ul[@class="names"]/li');
		$nodes->each(function(Crawler $node, $i) use (&$names) {
			$country = $node->filterXPath('//img')->attr('alt');
			$countryCode = $this->getCountryCode($country);

			$name = trim($node->filterXPath('//h3')->text());
			$names[$countryCode] = $name;
		});

		ksort($names);
		return $names;
	}

}
