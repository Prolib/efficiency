<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Utils\AdminPresenter;

use Nette\Application\UI\Presenter;
use Nette\SmartObject;

final class DefaultAction {

	use SmartObject;

	/** @var Presenter */
	protected $presenter;

	/** @var bool */
	protected $use;

	public function __construct(Presenter $presenter) {
		$this->presenter = $presenter;
		$this->use = $presenter->action === 'default';
	}

	public function addControlPanel(string $control, string $title): self {
		if ($this->use) {
			$template = $this->presenter->getTemplate();
			$template->panels[] = [
				'control' => $control,
				'title' => $title,
			];
		}

		return $this;
	}

}
