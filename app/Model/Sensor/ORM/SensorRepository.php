<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ORM;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Maisner\SmartHome\Model\Utils\Exceptions\EntityNotFoundException;

class SensorRepository {

	/** @var EntityManager */
	protected $em;

	/** @var EntityRepository */
	protected $repository;

	/**
	 * SensorRepository constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;

		/** @var EntityRepository $repository */
		$repository = $em->getRepository(Sensor::class);
		$this->repository = $repository;
	}

	/**
	 * @param int $id
	 * @return Sensor
	 * @throws EntityNotFoundException
	 */
	public function getById(int $id): Sensor {
		$sensor = $this->repository->findOneBy(['id' => $id]);

		if (!$sensor instanceof Sensor) {
			throw new EntityNotFoundException(Sensor::class, $id);
		}

		return $sensor;
	}

	/**
	 * @param int                 $sensorGroupId
	 * @param array|string[]|null $orderBy
	 * @param int|null            $limit
	 * @param int|null            $offset
	 * @return array|Sensor[]
	 */
	public function findByGroup(
		int $sensorGroupId,
		?array $orderBy = NULL,
		?int $limit = NULL,
		?int $offset = NULL
	): array {
		return $this->repository->findBy(['group' => $sensorGroupId], $orderBy, $limit, $offset);
	}

	/**
	 * @param array|string[]|null $orderBy
	 * @param int|NULL            $limit
	 * @param int|NULL            $offset
	 * @return array|Sensor[]
	 */
	public function findAll(?array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL): array {
		return $this->repository->findBy([], $orderBy, $limit, $offset);
	}
}