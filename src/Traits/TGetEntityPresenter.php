<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Traits;

use ProLib\Efficiency\Exceptions\EntityNotFoundException;

trait TGetEntityPresenter {

	/** @var object[] */
	private $_entityCache = [];

	/**
	 * @param string $class
	 * @return object
	 * @throws EntityNotFoundException
	 */
	protected function getEntityById(string $class): object {
		if (!($entity = $this->getEntityByIdSafe($class))) {
			throw new EntityNotFoundException($class, $this->getParameter('id'));
		}

		return $entity;
	}

	protected function getEntityByIdSafe(string $class): ?object {
		if (!array_key_exists($class, $this->_entityCache)) {
			$id = $this->getParameter('id');
			if ($id) {
				$this->_entityCache[$class] = $this->em->getRepository($class)->find((int) $id);
			} else {
				$this->_entityCache[$class] = null;
			}
		}

		return $this->_entityCache[$class];
	}

}
