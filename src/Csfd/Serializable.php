<?php

namespace Csfd;


class Serializable implements \JsonSerializable
{

	public function jsonSerialize() {
		$data = [];
		foreach ($this as $key => $value) {
			if ($value instanceof \DateTime) {
				$data[$key] = $value->format('c');
			} else if ($value) {
				$data[$key] = $value;
			}
		}

		return $data;
	}

}
