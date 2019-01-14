<?php declare(strict_types = 1);

namespace ProLib\Efficiency\DI;

use Nette\DI\CompilerExtension;
use ProLib\Efficiency\Doctrine\UnitOfWork;

final class EfficiencyExtension extends CompilerExtension {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('unitOfWork'))
			->setType(UnitOfWork::class);
	}

}
