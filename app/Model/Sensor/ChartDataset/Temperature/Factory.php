<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ChartDataset\Temperature;


use Maisner\SmartHome\Model\Sensor\ORM\Sensor;
use Maisner\SmartHome\Model\Sensor\ORM\SensorData;
use Nette\InvalidArgumentException;

class Factory {
	public const DATE_FORMAT = 'G:i';

	/**
	 * @param array|SensorData[] $sensorDataList
	 * @return Dataset
	 */
	public static function create(array $sensorDataList): Dataset {
		if (\count($sensorDataList) === 0) {
			throw new InvalidArgumentException('Sensor data array is empty');
		}

		$temperatures = [];
		$humidities = [];
		$dates = [];
		$sensorName = '';

		/** @var SensorData $data */
		foreach ($sensorDataList as $data) {
			$json = \GuzzleHttp\json_decode($data->getData());

			$dates[] = $data->getCreatedAt()->format(self::DATE_FORMAT);
			$temperatures[] = (string)$json->temperature;
			$humidities[] = (string)$json->humidity;
			$sensorName = $data->getSensor()->getName();
		}

		return new Dataset($sensorName, $dates, $temperatures, $humidities);
	}

	/**
	 * @param array|array[] $array
	 * @param Sensor        $sensor
	 * @return Dataset
	 */
	public static function createFromArray(array $array, Sensor $sensor): Dataset {
		$temperatures = [];
		$humidities = [];
		$dates = [];

		foreach ($array as $key => $item) {
			if (\count($item) === 0) {
				$temperatures[] = NULL;
				$humidities[] = NULL;
				$dates[] = (string)$key;

				continue;
			}
			$temperatures[] = (string)$item['temperature_avg'];
			$humidities[] = (string)$item['humidity_avg'];
			$dates[] = (string)$key;
		}

		return new Dataset($sensor->getName(), $dates, $temperatures, $humidities);
	}
}