<?php

namespace Csfd;

use TestCase;


class CsfdTest extends TestCase
{

	/**
	 * @covers Csfd\Csfd::create()
	 * @covers Csfd\Csfd::__construct()
	 */
	public function testCreate()
	{
		$csfd = Csfd::create();
		$this->assertInstanceOf('Csfd\Csfd', $csfd);
	}

	/**
	 * @covers Csfd\Csfd::authenticate()
	 */
	public function testAuthenticate()
	{
		$csfd = Csfd::create();
		$csfd->authenticate('user', 'pass');
		$this->assertTrue(TRUE); // exception not thrown
	}

}
