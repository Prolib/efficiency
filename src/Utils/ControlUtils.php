<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Utils;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\SmartObject;

class ControlUtils {

	use SmartObject;

	/** @var Control */
	protected $control;

	public function __construct(Control $control) {
		$this->control = $control;
	}

	public function form(Form $form): FormUtils {
		return new FormUtils($form, $this->control);
	}

}
