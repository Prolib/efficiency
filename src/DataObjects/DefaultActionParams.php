<?php declare(strict_types = 1);

namespace ProLib\Efficiency\DataObjects;

use Nette\ComponentModel\IComponent;
use Nette\SmartObject;

final class DefaultActionParams {

	use SmartObject;

	/** @var IComponent[] */
	protected $components;

	public static function create(): self {
		return new self();
	}

	/**
	 * @return static
	 */
	public function addComponent(string $componentName, string $name) {
		$this->components[$componentName] = $name;

		return $this;
	}

	/**
	 * @return IComponent[]
	 */
	public function getComponents(): array {
		return $this->components;
	}

}
