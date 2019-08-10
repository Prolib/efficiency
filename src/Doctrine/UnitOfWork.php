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
	private $hydrator;

	public function __construct(EntityManagerInterface $em, IHydrator $hydrator) {
		$this->em = $em;
		$this->hydrator = $hydrator;
	}

	public function toArray($object, array $settings = []) {
		if ($object === null) {
			return [];
		}
		
		return $this->hydrator->toArray($object, $settings);
	}

	public function toFields($object, iterable $values, array $settings = []) {
		return $this->hydrator->toFields($object, $values, $settings);
	}

	public function prepare(string $class, $object, array $values, array $settings = []) {
		$entity = $this->hydrator->toFields($object === null ? $class : $object, $values, $settings);

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
