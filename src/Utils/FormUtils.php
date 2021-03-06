<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Utils;

use Nette\Application\IPresenter;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Link;
use Nette\Application\UI\Presenter;
use Nette\Forms\Controls\BaseControl;
use Nette\SmartObject;

class FormUtils {

	use SmartObject;

	/** @var Form */
	protected $form;

	/** @var IPresenter|Presenter */
	private $presenter;

	/** @var Control */
	protected $control;

	/** @var callable[] */
	protected $onSuccess = [];

	/** @var callable[] */
	protected $onError = [];

	/** @var bool[] */
	private $uses = [];

	public function __construct(Form $form, Control $control) {
		$form->onAnchor[] = function () use ($form) {
			$form->onSuccess[] = [$this, '__onSuccess'];
			$form->onError[] = [$this, '__onError'];
		};

		$this->form = $form;
		$this->control = $control;
	}

	public static function create(Form $form, Control $control): self {
		return new self($form, $control);
	}

	public function flashMessage(string $message) {
		$this->onSuccess[] = function () use ($message) {
			$this->control->flashMessage($message);
		};

		return $this;
	}

	public function flashErrors() {
		$this->checkUse(__METHOD__);

		$this->onError[] = function () {
			foreach ($this->form->getOwnErrors() as $error) {
				$this->control->flashMessage($error, 'error');
			}
			/** @var BaseControl $control */
			foreach ($this->form->getControls() as $control) {
				foreach ($control->getErrors() as $error) {
					$this->control->flashMessage($control->caption . ': ' . $error, 'error');
				}
			}
		};

		return $this;
	}

	public function redirect(string $destination, array $args = [], bool $presenter = false) {
		$this->checkUse(__METHOD__);

		$control = $this->control;
		if ($presenter) {
			$control = $this->getPresenter();
		}

		$this->onSuccess[] = function () use ($destination, $args, $control) {
			$control->redirect($destination, $args);
		};

		return $this;
	}

	public function redirectWithBacklink(string $parameterName = 'backlink') {
		$this->checkUse(__METHOD__);

		$backlink = $this->control->getParameter($parameterName);

		if (!$backlink) {
			return $this;
		}

		$this->form->onAnchor[] = function () use ($backlink): void {
			$link = $this->form->getAction();

			if ($link instanceof Link) {
				$link->setParameter('backlink', $backlink);
			}
		};

		$this->onSuccess[] = function () use ($backlink) {
			$this->control->getPresenter()->redirectUrl($backlink);
		};

		return $this;
	}

	public function refresh() {
		$this->checkUse(__METHOD__);

		$this->redirect('this');

		return $this;
	}

	private function checkUse(string $name): void {
		if (isset($this->uses[$name])) {
			throw new \LogicException('Cannot call method ' . $name . ' twice.');
		}

		$this->uses[$name] = true;
	}

	/**
	 * @internal
	 */
	public function __onSuccess(Form $form, array $values): void {
		foreach ($this->onSuccess as $success) {
			$success($values);
		}
	}

	/**
	 * @internal
	 */
	public function __onError(Form $form): void {
		foreach ($this->onError as $error) {
			$error($form);
		}
	}

	/**
	 * @return Presenter|IPresenter
	 */
	protected function getPresenter(): IPresenter {
		if (!$this->presenter) {
			$this->presenter = $this->control->getPresenter();
		}

		return $this->presenter;
	}

}
