<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Utils;

use Nette\Application\UI\Presenter;

interface IAdminPresenterUtilsFactory {

	public function create(Presenter $presenter): AdminPresenterUtils;

}
