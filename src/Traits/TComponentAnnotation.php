<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Nette\ComponentModel\IComponent;
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

				return $component;
			}
		}

		return null;
	}

}
