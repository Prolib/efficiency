<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use ProLib\Efficiency\Utils\AdminPresenterUtils;
use ProLib\Efficiency\Utils\IAdminPresenterUtilsFactory;

trait TAdminPresenter {

	final public function injectAdminPresenterUtilsFactory(IAdminPresenterUtilsFactory $adminPresenterUtilsFactory) {
		$this->onStartup[] = function () use ($adminPresenterUtilsFactory) {
			$this->initialize($adminPresenterUtilsFactory->create($this));
		};
	}

	abstract protected function initialize(AdminPresenterUtils $utils);

}
