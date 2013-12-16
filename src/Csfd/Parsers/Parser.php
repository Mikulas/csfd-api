<?php

namespace Csfd\Parsers;


class Parser
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
	
}
