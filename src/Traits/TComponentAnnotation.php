<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Utils\Json;

trait TComponentAnnotation {

	protected function createComponent(string $name): ?IComponent {
		if ($component = parent::createComponent($name)) {
			return $component;
		}
		if (property_exists($this, $name)) {
			$reflection = new \ReflectionProperty($this, $name);
			$comment = $reflection->getDocComment();
			if (preg_match('#@component(\((.*?)\))?#', $comment, $matches)) {
				$component = $this->$name;
				// options
				if (isset($matches[2])) {
					$options = Json::decode('{' . $matches[2] . '}', JSON_OBJECT_AS_ARRAY);

					$component = $this->adjustComponent($component, $options);
				}

				return $component;
			}
		}

		return null;
	}

	protected function adjustComponent($component, array $options): IComponent {
		// method
		if (isset($options['method'])) {
			$component = $component->{$options['method']}();
		}
		// form
		if (isset($options['form'])) {
			$component->onSuccess[] = function () use ($options): void {
				if (isset($options['flashMessage'])) {
					$this->flashMessage($options['flashMessage']);
				}

				$this->redirectRestore(is_string($options['form']) ? $options['form'] : 'this');
			};
		}
		// flashError
		if (isset($options['flashError']) && $component instanceof Form) {
			$component->onError[] = function (Form $form): void {
				foreach ($form->getErrors() as $error) {
					$this->flashMessage($error, 'error');
				}
			};
		}

		return $component;
	}

}
