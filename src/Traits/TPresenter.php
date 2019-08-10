<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Nette\ComponentModel\IComponent;

trait TPresenter {

	use TComponentAnnotation;
	use TFormAnnotation;
	use TGetEntityPresenter;

	protected function createComponent(string $name): ?IComponent {
		$component = parent::createComponent($name);
		if ($component) {
			return $component;
		}

		$component = $this->formAnnotation($name);
		if ($component) {
			return $component;
		}

		$component = $this->componentAnnotation($name);

		return $component;
	}

}
