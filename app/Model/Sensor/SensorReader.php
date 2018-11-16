<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Maisner\SmartHome\Model\Sensor\Exceptions\ReadDataException;
use Maisner\SmartHome\Model\Sensor\ORM\Sensor;
use Maisner\SmartHome\Model\Sensor\ORM\SensorData;
use Maisner\SmartHome\Model\Sensor\ORM\SensorDataRepository;
use Maisner\SmartHome\Model\Sensor\ORM\SensorGroup;
use Maisner\SmartHome\Model\Sensor\ORM\SensorRepository;
use Tracy\ILogger;

class SensorReader {

	public const LOG_NAME = 'sensor_read_data';

	/** @var SensorRepository */
	private $sensorRepository;

	/** @var ILogger */
	private $logger;

	/** @var SensorDataRepository */
	private $sensorDataRepository;

	/**
	 * SensorReader constructor.
	 * @param SensorRepository     $sensorRepository
	 * @param SensorDataRepository $sensorDataRepository
	 * @param ILogger              $logger
	 */
	public function __construct(
		SensorRepository $sensorRepository,
		SensorDataRepository $sensorDataRepository,
		ILogger $logger
	) {
		$this->sensorRepository = $sensorRepository;
		$this->sensorDataRepository = $sensorDataRepository;
		$this->logger = $logger;
	}

	/**
	 * @param SensorGroup $sensorGroup
	 */
	public function readByGroup(SensorGroup $sensorGroup): void {
		$this->readDataFromSensors($this->sensorRepository->findByGroup($sensorGroup->getId()));
	}

	public function readAll(): void {
		$this->readDataFromSensors($this->sensorRepository->findAll());
	}

	/**
	 * @param array|Sensor[] $sensors
	 */
	protected function readDataFromSensors(array $sensors): void {
		foreach ($sensors as $sensor) {
			try {
				$data = $this->readDataSingleSensor($sensor);
				$this->sensorDataRepository->insert(new SensorData(new \DateTimeImmutable(), $sensor, $data));
			} catch (ReadDataException | \Throwable $e) {
				$this->logger->log($e, self::LOG_NAME);
			}
		}
	}

	/**
	 * @param Sensor $sensor
	 * @return string
	 */
	protected function readDataSingleSensor(Sensor $sensor): string {
		try {
			$client = new Client();
			$res = $client->request('GET', $sensor->getUrl());
		} catch (GuzzleException $e) {
			$this->logger->log($e, self::LOG_NAME);

			$msg = \sprintf('Read data from sensor with id "%s" failed', $sensor->getId());
			throw new ReadDataException($msg, 0, $e);
		}

		if ($res->getStatusCode() !== 200) {
			throw new ReadDataException(
				\sprintf('Response code from sensor must be 200. Given %s', $res->getStatusCode())
			);
		}

		return $res->getBody()->getContents();
	}
}