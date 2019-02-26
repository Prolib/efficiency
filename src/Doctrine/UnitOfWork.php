<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Doctrine;

use Nette\SmartObject;
use Doctrine\ORM\EntityManagerInterface;
use Nettrine\Hydrator\IHydrator;

class UnitOfWork {

	use SmartObject;

	/** @var EntityManagerInterface */
	private $em;

	/** @var IHydrator */
	private $hydration;

	public function __construct(EntityManagerInterface $em, IHydration $hydration) {
		$this->em = $em;
		$this->hydration = $hydration;
	}

	public function toArray($object, array $settings = []) {
		return $this->hydration->toArray($object, $settings);
	}

	public function toFields($object, iterable $values, array $settings = []) {
		return $this->hydration->toFields($object, $values, $settings);
	}

	public function prepare(string $class, $object, array $values, array $settings = []) {
		$entity = $this->hydration->toFields($object === null ? $class : $object, $values, $settings);

		if (!$object) {
			$this->em->persist($entity);
		} else {
			$this->em->merge($entity);
		}

		return $entity;
	}

	public function work(string $class, $object, array $values, array $settings = []) {
		$entity = $this->prepare($class, $object, $values, $settings);
		$this->em->flush();

		return $entity;
	}

}
