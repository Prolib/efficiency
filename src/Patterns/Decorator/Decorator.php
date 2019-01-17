<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Patterns\Decorator;

abstract class Decorator {

	/** @var object */
	private $decorator;

	public function __construct($decorator) {
		$this->decorator = $decorator;
	}

	public function __get(string $name) {
		return DecoratorMixins::get($this->decorator, $name);
	}

	public function __call(string $name, array $arguments) {
		return DecoratorMixins::call($this->decorator, $name, $arguments);
	}

	public function __set(string $name, $value): void {
		DecoratorMixins::set($this->decorator, $name, $value);
	}

	public function __isset(string $name): bool {
		return DecoratorMixins::isset($this->decorator, $name);
	}

	public function __unset(string $name): void {
		DecoratorMixins::unset($this->decorator, $name);
	}

}
