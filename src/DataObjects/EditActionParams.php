<?php declare(strict_types = 1);

namespace Thunbolt\Administration\DataObjects;

final class EditActionParams {

	/** @var object */
	private $model;

	/** @var string */
	private $class;

	/** @var string */
	private $name;

	/** @var string */
	private $controlName;

	/** @var string|null */
	private $property;

	/**
	 * @param string $entityClass
	 * @param string $name
	 * @param string $controlName
	 * @param string|bool|null $property skip if property === false
	 */
	public function __construct(string $entityClass, string $name, string $controlName, $property = null) {
		$this->class = $entityClass;
		$this->name = $name;
		$this->controlName = $controlName;
		$this->property = $property;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getSetter(): string {
		return 'set' . ucfirst(substr($this->class, strrpos($this->class, '\\') + 1));
	}

	/**
	 * @return string
	 */
	public function getControlName(): string {
		return $this->controlName;
	}

	/**
	 * @return object
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @return string
	 */
	public function getClass(): string {
		return $this->class;
	}

	/**
	 * @return string|null
	 */
	public function getProperty(): ?string {
		if ($this->property === false) {
			return null;
		}

		return $this->property ?: $this->getControlName();
	}

}
