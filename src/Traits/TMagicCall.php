<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Nette\Utils\ObjectHelpers;

trait TMagicCall {

	public function __call($name, $arguments) {
		if (substr($name, 0, 3) === 'get') {
			$name = lcfirst(substr($name, 3));

			return $this->$name;
		}
		if (substr($name, 0, 2) === 'is') {
			$name = lcfirst(substr($name, 2));

			return $this->$name;
		}

		parent::__call($name, $arguments);
	}

}
