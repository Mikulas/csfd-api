<?php

namespace Csfd;


class Message
{

	/** @var User */
	protected $sender;

	/** @var User */
	protected $receiver;

	/** @var html */
	protected $body;

	/** @var bool */
	protected $read; // read by receiver if I am sender

	/** @var DateTime */
	protected $date;

	public function delete()
	{

	}

	public function moveToFolder()
	{

	}

}
