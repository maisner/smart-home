<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Maisner\SmartHome\Model\Sensor\ORM\Sensor;
use Maisner\SmartHome\Model\Sensor\ORM\SensorData;
use Maisner\SmartHome\Model\Sensor\ORM\SensorDataRepository;

class SensorDataProvider {

	/** @var SensorDataRepository */
	private $sensorDataRepository;

	/** @var Connection */
	private $connection;

	public function __construct(SensorDataRepository $sensorDataRepository, Connection $connection) {
		$this->sensorDataRepository = $sensorDataRepository;
		$this->connection = $connection;
	}

	/**
	 * @param \DateTimeImmutable $day
	 * @param Sensor             $sensor
	 * @return array|SensorData[]
	 */
	public function findByDay(\DateTimeImmutable $day, Sensor $sensor): array {
		return $this->sensorDataRepository->findByDay($sensor->getId(), $day);
	}

	/**
	 * @param int                $sensorId
	 * @param \DateTimeImmutable $start
	 * @param \DateTimeImmutable $end
	 * @return array|array[]
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function getDayAvarageValues(int $sensorId, \DateTimeImmutable $start, \DateTimeImmutable $end): array {
		$days = $this->formatDatesFromDatePeriod($this->getAllDatesBetweenTwoDays($start, $end));

		$sql = '
			SELECT DATE(`created_at`) AS `day`, 
			COUNT(*) AS `count`, `sensor_id`, 
			AVG(JSON_EXTRACT(`data`, "$.temperature")) AS `temperature_avg`, AVG(JSON_EXTRACT(`data`, "$.humidity")) AS `humidity_avg`
			FROM `sensor_data` 
			WHERE `sensor_id` = ? AND
			DATE(`created_at`) IN (?)
			GROUP BY DATE(`created_at`)';

		$params = [
			$sensorId,
			$days
		];

		$types = [
			ParameterType::INTEGER,
			Connection::PARAM_STR_ARRAY
		];

		$rows = $this->connection->executeQuery($sql, $params, $types)->fetchAll();

		$result = [];
		foreach ($days as $day) {
			$data = [];
			foreach ($rows as $row) {
				if ($row['day'] === $day) {
					$data = $row;
					break;
				}
			}

			$result[$day] = $data;
		}

		return $result;
	}

	/**
	 * @param \DatePeriod $period
	 * @return array|string[]
	 */
	private function formatDatesFromDatePeriod(\DatePeriod $period): array {
		$days = [];

		/** @var \DateTimeInterface $date */
		foreach ($period as $date) {
			$days[] = $date->format('Y-m-d');
		}

		return $days;
	}

	/**
	 * @param \DateTimeImmutable $start
	 * @param \DateTimeImmutable $end
	 * @return \DatePeriod
	 * @throws \Exception
	 */
	private function getAllDatesBetweenTwoDays(\DateTimeImmutable $start, \DateTimeImmutable $end): \DatePeriod {
		return new \DatePeriod($start->setTime(0, 0), new \DateInterval('P1D'), $end->setTime(23, 59, 59));
	}
}