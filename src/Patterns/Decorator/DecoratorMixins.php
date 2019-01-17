<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Patterns\Decorator;

use Nette\SmartObject;

final class DecoratorMixins {

	use SmartObject;

	public static function get($decorator, string $name) {
		return $decorator->$name;
	}

	public static function call($decorator, string $name, array $arguments) {
		return $decorator->$name(...$arguments);
	}

	public static function set($decorator, string $name, $value): void {
		$decorator->$name = $value;
	}

	public static function isset($decorator, string $name): bool {
		return isset($decorator->$name);
	}

	public static function unset($decorator, string $name): void {
		unset($decorator->$name);
	}

}
