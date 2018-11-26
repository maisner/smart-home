<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\BasicDataProvider\Temperature;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

class AverageValuesProvider {

	/** @var Connection */
	private $connection;

	/**
	 * AverageValuesProvider constructor.
	 * @param Connection $connection
	 */
	public function __construct(Connection $connection) {
		$this->connection = $connection;
	}

	/**
	 * Return average values for hours
	 *
	 * @param int                $sensorId
	 * @param \DateTimeImmutable $start
	 * @param \DateTimeImmutable $end
	 * @return array|array[]
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function getHourAverageValues(int $sensorId, \DateTimeImmutable $start, \DateTimeImmutable $end): array {
		$dayHours = $this->formatDatesFromDatePeriod($this->getAllHoursBetweenTwoDays($start, $end), 'G');

		$sql = '
			SELECT DATE(`created_at`) AS `day`, HOUR(`created_at`) AS `hour`,
			COUNT(*) AS `count`, `sensor_id`, 
			AVG(JSON_EXTRACT(`data`, "$.temperature")) AS `temperature_avg`, AVG(JSON_EXTRACT(`data`, "$.humidity")) AS `humidity_avg`
			FROM `sensor_data` 
			WHERE `sensor_id` = ? AND
			CONCAT(DATE(`created_at`), " ", HOUR(`created_at`)) IN (?) 
			GROUP BY DATE(`created_at`), HOUR(`created_at`)
			ORDER BY DATE(`created_at`) ASC, HOUR(`created_at`) ASC';

		$params = [
			$sensorId,
			$dayHours
		];

		$types = [
			ParameterType::INTEGER,
			Connection::PARAM_STR_ARRAY
		];

		$rows = $this->connection->executeQuery($sql, $params, $types)->fetchAll();

		$result = [];
		foreach ($dayHours as $dayHour) {
			$data = [];
			foreach ($rows as $row) {
				if (\sprintf('%s %s', $row['day'], $row['hour']) === $dayHour) {
					$data = $row;
					break;
				}
			}

			$result[$dayHour] = $data;
		}

		return $result;
	}

	/**
	 * Return average values for days
	 *
	 * @param int                $sensorId
	 * @param \DateTimeImmutable $start
	 * @param \DateTimeImmutable $end
	 * @return array|array[]
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function getDayAverageValues(int $sensorId, \DateTimeImmutable $start, \DateTimeImmutable $end): array {
		$days = $this->formatDatesFromDatePeriod($this->getAllDaysBetweenTwoDays($start, $end));

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
	 * @param null|string $timeFormat example: H:i:s
	 * @return array|string[]
	 */
	private function formatDatesFromDatePeriod(\DatePeriod $period, ?string $timeFormat = NULL): array {
		$days = [];

		/** @var \DateTimeInterface $date */
		foreach ($period as $date) {
			$days[] = $date->format($timeFormat !== NULL ? \sprintf('Y-m-d %s', $timeFormat) : 'Y-m-d');
		}

		return $days;
	}

	/**
	 * @param \DateTimeImmutable $start
	 * @param \DateTimeImmutable $end
	 * @return \DatePeriod
	 * @throws \Exception
	 */
	private function getAllDaysBetweenTwoDays(\DateTimeImmutable $start, \DateTimeImmutable $end): \DatePeriod {
		return new \DatePeriod($start->setTime(0, 0), new \DateInterval('P1D'), $end->setTime(23, 59, 59));
	}

	/**
	 * @param \DateTimeImmutable $start
	 * @param \DateTimeImmutable $end
	 * @return \DatePeriod
	 * @throws \Exception
	 */
	private function getAllHoursBetweenTwoDays(\DateTimeImmutable $start, \DateTimeImmutable $end): \DatePeriod {
		return new \DatePeriod($start->setTime(0, 0), new \DateInterval('PT1H'), $end->setTime(23, 59, 59));
	}
}