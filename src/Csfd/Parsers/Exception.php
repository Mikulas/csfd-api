<?php

namespace Csfd\Parsers;

use Csfd\Exception as CsdfException;


class Exception extends CsdfException
{

	// Parser
	const FORM_NOT_FOUND = 1;
	const TOKEN_NOT_FOUND = 2;
	const NODE_NOT_FOUND = 3;
	const URL_ID_NOT_FOUND = 4;

	// User
	const USER_NODE_NOT_FOUND = 1001;

}
