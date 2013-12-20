<?php

namespace Csfd\Authentication;

use Csfd\Exception as CsfdException;


class Exception extends CsfdException
{

	const CREDENTIALS_NOT_SET = 1;
	const INVALID_CREDENTIALS = 2;
	const NOT_AUTHENTICATED = 3;

}
