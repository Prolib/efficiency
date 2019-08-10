<?php declare(strict_types = 1);

namespace ProLib\Efficiency\DI;

use Nette\DI\CompilerExtension;
use ProLib\Efficiency\Doctrine\UnitOfWork;
use ProLib\Efficiency\Utils\IAdminPresenterUtilsFactory;

final class EfficiencyExtension extends CompilerExtension {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('unitOfWork'))
			->setType(UnitOfWork::class);

		$builder->addFactoryDefinition($this->prefix('adminPresenterUtilsFactory'))
			->setImplement(IAdminPresenterUtilsFactory::class);
	}

}
