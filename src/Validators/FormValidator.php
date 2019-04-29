<?php declare(strict_types = 1);

namespace ProLib\Efficiency\Validators;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Nette\Forms\IControl;
use Nette\StaticClass;
use ProLib\Efficiency\Validators\VO\VOFormValidator;

class FormValidator {

	use StaticClass;

	/**
	 * if value exists in database, validate fails
	 *
	 * Mustn't exists in database
	 */
	public static function validateNotExists(IControl $control, VOFormValidator $value): bool {
		if ($value->default === $control->getValue()) {
			return true;
		}

		return !self::exists($value->repository, $value->column ?? $control->getName(), $control->getValue());
	}

	/**
	 * if value not exists in database, validate fails
	 *
	 * Must exists in database
	 */
	public static function validateExists(IControl $control, VOFormValidator $value): bool {
		if ($value->default === $control->getValue()) {
			return true;
		}

		return self::exists($value->repository, $value->column ?? $control->getName(), $control->getValue());
	}

	private static function exists(EntityRepository $repository, string $name, $value): bool {
		try {
			return (bool) $repository->createQueryBuilder('e')
				->where("e.$name = :val")
				->setParameter('val', $value)
				->select('1')
				->setMaxResults(1)
				->getQuery()->getSingleScalarResult();
		} catch (NoResultException $e) {
			return false;
		}
	}

}
