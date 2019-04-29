<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Helpers;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use ProLib\Efficiency\Exceptions\ComponentCreationException;
use Reflector;

/**
 * @internal
 */
final class EfficiencyHelper {

	public static function loadAnnotationOptions(string $annotation, Reflector $reflector): ?array {
		if (preg_match('#@' . $annotation . '(\((.*?)\))?#', $reflector->getDocComment(), $matches)) {
			if (!isset($matches[2]) || !($options = trim($matches[2]))) {
				return [];
			}
			try {
				return Json::decode('{' . $options . '}', JSON_OBJECT_AS_ARRAY);
			} catch (JsonException $e) {
				throw ComponentCreationException::createJson($reflector, $e);
			}
		}

		return null;
	}

	public static function checkObject($component, Reflector $reflector): void {
		if (!is_object($component)) {
			throw ComponentCreationException::createInvalidProperty(
				sprintf('must be object, %s given', gettype($component)), $reflector
			);
		}
	}

	public static function factoryMethod(array $options, object $component, Reflector $reflector) {
		if (isset($options['factory'])) {
			if (!method_exists($component, $options['factory'])) {
				throw ComponentCreationException::createInvalidAnnotation(
					sprintf('method %s not exists', $options['factory']), $reflector
				);
			}

			return $component->{$options['factory']}();
		}

		return null;
	}

}
