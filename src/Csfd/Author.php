<?php

namespace Csfd;


class Author extends Serializable
{

	/** @var int */
	protected $id;

	/** @var string */
	protected $name;



	public function __construct($id)
	{
		$this->id = $id;
	}



	public function setName($name)
	{
		$this->name = $name;
	}

}
