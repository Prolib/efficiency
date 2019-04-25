<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use ProLib\Efficiency\Exceptions\ComponentCreationException;
use ProLib\Efficiency\Helpers\EfficiencyHelper;

trait TComponentAnnotation {

	private function componentAnnotation(string $name): ?IComponent {
		if (property_exists($this, $name)) {
			$reflection = new \ReflectionProperty($this, $name);
			$options = EfficiencyHelper::loadAnnotationOptions('component', $reflection);
			if ($options !== null) {
				$component = $this->$name;
				if ($ret = EfficiencyHelper::factoryMethod($options, $component, $reflection)) {
					$component = $ret;
				}

				return $this->_adjustComponent($component, $options);
			}
		}

		return null;
	}

	private function _adjustComponent($component, array $options): IComponent {
		// method
		// deprecated way
		if (isset($options['method'])) {
			$options['factory'] = $options['method'];
		}

		if (isset($options['factory'])) {
			$component = $component->{$options['factory']}();
		}

		return $component;
	}

}
