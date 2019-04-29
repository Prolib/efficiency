<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Validators\VO;

use Doctrine\ORM\EntityRepository;
use Nette\SmartObject;

class VOFormValidator {

	use SmartObject;

	/** @var EntityRepository */
	public $repository;

	/** @var mixed */
	public $default;

	/** @var string|null */
	public $column;

	public function __construct(EntityRepository $repository, $default = null, ?string $column = null) {
		$this->repository = $repository;
		$this->default = $default;
		$this->column = $column;
	}

}
