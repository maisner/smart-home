<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ORM;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Tracy\ILogger;

class SensorDataRepository {

	/** @var EntityManager */
	protected $em;

	/** @var EntityRepository */
	protected $repository;

	/** @var ILogger */
	protected $logger;

	/**
	 * SensorDataRepository constructor.
	 * @param EntityManager $em
	 * @param ILogger       $logger
	 */
	public function __construct(EntityManager $em, ILogger $logger) {
		$this->em = $em;
		$this->logger = $logger;

		/** @var EntityRepository $repository */
		$repository = $em->getRepository(SensorData::class);
		$this->repository = $repository;
	}

	/**
	 * @param int                 $sensorId
	 * @param array|string[]|null $orderBy
	 * @param int|null            $limit
	 * @param int|null            $offset
	 * @return array|SensorData[]
	 */
	public function findBySensor(
		int $sensorId,
		?array $orderBy = NULL,
		?int $limit = NULL,
		?int $offset = NULL
	): array {
		return $this->repository->findBy(['sensor' => $sensorId], $orderBy, $limit, $offset);
	}

	/**
	 * @param SensorData $sensorData
	 * @return bool
	 */
	public function insert(SensorData $sensorData): bool {
		try {
			$this->em->persist($sensorData);
			$this->em->flush();
		} catch (OptimisticLockException | ORMException $e) {
			$this->logger->log($e, ILogger::ERROR);

			return FALSE;
		}

		return TRUE;
	}
}