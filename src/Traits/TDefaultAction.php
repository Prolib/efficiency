<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use ProLib\Efficiency\DataObjects\DefaultActionParams;

trait TDefaultAction {

	abstract protected function _getDefaultActionParams(): DefaultActionParams;

	protected function _defaultCallback(DefaultActionParams $params) {
		$template = $this->getTemplate();
		$template->components = $params->getComponents();

		$template->setFile(__DIR__ . '/templates/default.latte');
	}
	
	public function actionDefault() {
		$this->_defaultCallback($this->_getDefaultActionParams());
	}

}
