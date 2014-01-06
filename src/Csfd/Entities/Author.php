<?php

namespace Csfd\Entities;

use Csfd\Authentication\Authenticator;
use Csfd\Networking\RequestFactory;
use Csfd\Networking\UrlBuilder;
use Csfd\Parsers\Parser;


/**
 */
class Author extends Entity
{

	const DIRECTOR = 'director';
	const WRITER = 'writer';
	const ACTOR = 'actor';
	const COMPOSER = 'composer';
	const CAMERA = 'camera'; // director of photography

}
