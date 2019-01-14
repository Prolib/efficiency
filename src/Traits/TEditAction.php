<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Thunbolt\Administration\DataObjects\EditActionParams;

trait TEditAction {

	/** @var EntityManagerInterface @inject */
	public $em;

	abstract protected function _getEditActionParams(): EditActionParams;

	protected function _editCallback(int $id): void {
		$params = $this->_getEditActionParams();
		if (!($row = $this->em->getRepository($params->getClass())->find($id))) {
			$this->error();
		}

		$template = $this->getTemplate();

		foreach ($this->formatTemplateFiles() as $source) {
			if (file_exists($source)) {
				$templateFile = $source;
			}
		}

		$file = __DIR__ . '/templates/edit.latte';
		if (isset($templateFile)) {
			$template->parent = $file;
			$template->setFile($templateFile);
		} else {
			$template->setFile($file);
		}

		$template->name = $params->getName();
		$template->controlName = $control = $params->getControlName();

		if ($params->getProperty()) {
			$this->{$params->getProperty()}->{$params->getSetter()}($row);
		}
	}

	public function actionEdit(int $id): void {
		$this->_editCallback($id);
	}

}
