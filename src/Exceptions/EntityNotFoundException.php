<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Exceptions;

use Nette\Application\BadRequestException;

class EntityNotFoundException extends BadRequestException {

	public function __construct(string $class, $id) {
		parent::__construct("Entity $class($id) not found.");
	}

}
