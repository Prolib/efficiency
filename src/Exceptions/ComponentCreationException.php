<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Exceptions;

use Throwable;

class ComponentCreationException extends \Exception {

	public static function createJson(\ReflectionProperty $property, Throwable $previous): self {
		return self::createInvalidAnnotation('invalid json: ' . $previous->getMessage(), $property, $previous);
	}

	public static function createInvalidAnnotation(string $message, \ReflectionProperty $property, ?Throwable $previous = null): self {
		$message = sprintf('Invalid annotation in property %s::$%s: %s',
			$property->getDeclaringClass()->getName(), $property->getName(), $message
		);

		return new self($message, 0, $previous);
	}

	public static function createInvalidProperty(string $message, \ReflectionProperty $property, ?Throwable $previous = null): self {
		$message = sprintf('Invalid property %s::$%s: %s',
			$property->getDeclaringClass()->getName(), $property->getName(), $message
		);

		return new self($message, 0, $previous);
	}

}
