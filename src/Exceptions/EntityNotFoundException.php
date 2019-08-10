<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Exceptions;

use Throwable;

class EntityNotFoundException extends \RuntimeException {

	public function __construct(string $class, $id) {
		parent::__construct("Entity $class($id) not found.");
	}

}
