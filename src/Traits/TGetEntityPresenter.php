<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use ProLib\Efficiency\Exceptions\EntityNotFoundException;

trait TGetEntityPresenter {

	/** @var object[] */
	private $entityCache = [];

	/**
	 * @param string $class
	 * @return null|object|mixed
	 * @throws EntityNotFoundException
	 */
	protected function getEntityById(string $class) {
		if (!isset($this->entityCache[$class])) {
			$id = $this->getParameter('id');
			if ($id && $row = $this->em->getRepository($class)->find((int) $id)) {
				$this->entityCache[$class] = $row;
			} else {
				throw new EntityNotFoundException($class, $id);
			}
		}

		return $this->entityCache[$class];
	}

}
