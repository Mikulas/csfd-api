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

}
