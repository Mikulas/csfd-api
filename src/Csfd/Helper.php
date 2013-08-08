<?php

namespace Csfd;


class Helper
{

	static function parseIdFromUrl($url)
	{
		$match = [];
		preg_match('~/(?P<id>\d+)-~', $url, $match);

		return (int) $match['id'];
	}



	static function addSchemaIfMissing($url)
	{
		if (strPos($url, '//') === 0) {
			$url = "http:$url";
		}
		return $url;
	}

}
