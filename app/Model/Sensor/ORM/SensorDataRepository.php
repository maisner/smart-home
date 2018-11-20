<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ORM;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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
	 * @param int                $sensorId
	 * @param \DateTimeImmutable $day
	 * @return array|SensorData[]
	 */
	public function findByDay(int $sensorId, \DateTimeImmutable $day): array {
		$sql = '
			SELECT * FROM sensor_data d WHERE
			`d`.`sensor_id` = ? AND
			DATE(`d`.`created_at`) = ?
			';

		$params = [
			$sensorId,
			$day->format('Y-m-d')
		];

		$rsm = new ResultSetMappingBuilder($this->em);
		$rsm->addRootEntityFromClassMetadata(SensorData::class, 'd');

		return $this->em->createNativeQuery($sql, $rsm)
			->setParameters($params)
			->getResult();
	}

	/**
	 * @param int                $sensorId
	 * @param \DateTimeImmutable $day
	 * @param int                $hour
	 * @return array|SensorData[]
	 */
	public function findByDayHour(int $sensorId, \DateTimeImmutable $day, int $hour): array {
		$sql = '
			SELECT * FROM sensor_data d WHERE
			`d`.`sensor_id` = ? AND
			DATE(`d`.`created_at`) = ? AND
			HOUR(`d`.`created_at`) = ? 
			';

		$params = [
			$sensorId,
			$day->format('Y-m-d'),
			$hour
		];

		$rsm = new ResultSetMappingBuilder($this->em);
		$rsm->addRootEntityFromClassMetadata(SensorData::class, 'd');

		return $this->em->createNativeQuery($sql, $rsm)
			->setParameters($params)
			->getResult();
	}

	/**
	 * @param int $sensorId
	 * @param int $hour
	 * @return array|SensorData[]
	 */
	public function findByHour(int $sensorId, int $hour): array {
		$sql = '
			SELECT * FROM sensor_data d WHERE
			`d`.`sensor_id` = ? AND
			HOUR(`d`.`created_at`) = ? 
			';

		$params = [
			$sensorId,
			$hour
		];

		$rsm = new ResultSetMappingBuilder($this->em);
		$rsm->addRootEntityFromClassMetadata(SensorData::class, 'd');

		return $this->em->createNativeQuery($sql, $rsm)
			->setParameters($params)
			->getResult();
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