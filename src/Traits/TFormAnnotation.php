<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Forms\Controls\BaseControl;
use ProLib\Efficiency\Exceptions\ComponentCreationException;
use ProLib\Efficiency\Helpers\EfficiencyHelper;

/**
 * Options:
 * 		factory: string - factory method (defaults: null)
 * 		message: string - onSuccess message
 * 		redirect: string - onSuccess redirection (defaults: this)
 * 		flashErrors: bool - errors to flashMessages (defaults: false)
 * 		ajax: string|array - ajax snippets (defaults: false)
 */
trait TFormAnnotation {

	private function formAnnotation(string $name): ?IComponent {
		if (property_exists($this, $name)) {
			$reflection = new \ReflectionProperty($this, $name);

			$options = EfficiencyHelper::loadAnnotationOptions('form', $reflection);
			if ($options) {
				$component = $this->$name;
				EfficiencyHelper::checkObject($component, $reflection);

				if ($ret = EfficiencyHelper::factoryMethod($options, $component, $reflection)) {
					$component = $ret;
				}

				if (!$component instanceof Form) {
					throw ComponentCreationException::createInvalidProperty(
						sprintf('must be instance of %s, %s given', Form::class, get_class($component)), $reflection
					);
				}

				$this->_adjustForm($component, $options);

				return $component;
			}
		}

		return null;
	}
	
	private function _adjustForm(Form $form, array $options): void {
		$options['ajax'] = $options['ajax'] ?? false;
		$options['redirect'] = $options['redirect'] ?? 'this';

		$form->onSuccess[] = function () use ($options): void {
			if (isset($options['message'])) {
				$this->flashMessage($options['message'], 'success');
			}

			if ($options['ajax'] && $this->isAjax()) {
				foreach ((array) $options['ajax'] as $snippet) {
					$this->redrawControl($snippet);
				}
			} else {
				$this->redirect($options['redirect']);
			}
		};

		$form->onError[] = function (Form $form) use ($options): void {
			if (isset($options['flashErrors']) && $options['flashErrors']) {
				foreach ($form->getOwnErrors() as $error) {
					$this->flashMessage($error, 'error');
				}
				/** @var BaseControl $control */
				foreach ($form->getControls() as $control) {
					foreach ($control->getErrors() as $error) {
						$this->flashMessage($control->caption . ': ' . $error, 'error');
					}
				}
			}
		};
	}

}
