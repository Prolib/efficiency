<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\Presenter;
use ProLib\Efficiency\Utils\AdminPresenter\DefaultAction;

final class AdminPresenterUtils {

	/** @var Presenter */
	private $presenter;
	
	/** @var EntityManagerInterface */
	private $em;
	
	public function __construct(Presenter $presenter, EntityManagerInterface $em) {
		$this->presenter = $presenter;
		$this->em = $em;
	}

	protected function createTemplate(string $templateFile): ITemplate {
		$template = $this->presenter->getTemplate();

		$original = null;
		foreach ($this->presenter->formatTemplateFiles() as $source) {
			if (file_exists($source)) {
				$original = $source;
			}
		}

		if ($original) {
			$template->parent = $templateFile;
			$template->setFile($original);
		} else {
			$template->setFile($templateFile);
		}

		return $template;
	}

	public function createDefaultAction(): DefaultAction {
		if ($this->presenter->action === 'default') {
			$template = $this->createTemplate(__DIR__ . '/templates/default.latte');

			$template->panels = [];
		}

		return new DefaultAction($this->presenter);
	}

	public function setEditAction(string $entityClass, string $panelTitle, string $controlName, ?string $property = null, $id = null): void {
		if ($this->presenter->action !== 'edit') {
			return;
		}
		if (!method_exists($this->presenter, 'actionEdit') && !method_exists($this->presenter, 'renderEdit')) {
			throw new UtilsException(sprintf('Presenter %s must have actionEdit or renderEdit method with paremeter $id', get_class($this->presenter)));
		}
		if ($id === null) {
			$id = $this->presenter->getParameter('id');
		}

		if (!$id || !($row = $this->em->getRepository($entityClass)->find($id))) {
			$this->presenter->error();
		}

		$template = $this->createTemplate(__DIR__ . '/templates/edit.latte');

		$template->name = $panelTitle;
		$template->controlName = $controlName;

		if ($property) {
			if (!property_exists($this->presenter, $property)) {
				throw new UtilsException(sprintf('Property %s not exists in %s class', $property, get_class($this->presenter)));
			}
			$setter = 'set' . ucfirst(substr($entityClass, strrpos($entityClass, '\\') + 1));
			$control = $this->presenter->{$property};
			if (!method_exists($control, $setter)) {
				throw new UtilsException(sprintf('Setter %s not exists in %s', $setter, get_class($control)));
			}
			$control->{$setter}($row);
		}
	}
	
}
