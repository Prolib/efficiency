<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Nette\ComponentModel\IComponent;
use ProLib\Efficiency\Exceptions\ComponentCreationException;
use ProLib\Efficiency\Helpers\EfficiencyHelper;
use Throwable;

trait TComponentAnnotation {

	private function componentAnnotation(string $name): ?IComponent {
		if (property_exists($this, $name)) {
			$reflection = new \ReflectionProperty($this, $name);
			try {
				$options = EfficiencyHelper::loadAnnotationOptions('component', $reflection);
				if ($options !== null) {
					$component = $this->$name;
					if ($ret = EfficiencyHelper::factoryMethod($options, $component, $reflection)) {
						$component = $ret;
					}

					return $component;
				}
			} catch (Throwable $e) {
				if ($e instanceof ComponentCreationException) {
					throw $e;
				} else {
					throw ComponentCreationException::createInvalidProperty($e->getMessage(), $reflection, $e);
				}
			}
		}

		return null;
	}

}
