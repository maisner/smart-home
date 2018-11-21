<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ChartData;


use Maisner\SmartHome\Model\Sensor\ORM\Sensor;
use Maisner\SmartHome\Model\Sensor\ORM\SensorData;
use Nette\InvalidArgumentException;

class TemperatureChartDataFactory {

	public const DATE_FORMAT = 'G:i';

	/**
	 * @param array|SensorData[] $sensorDataList
	 * @return TemperatureChartData
	 */
	public static function create(array $sensorDataList): TemperatureChartData {
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

		return new TemperatureChartData($sensorName, $dates, $temperatures, $humidities);
	}

	/**
	 * @param array|array[] $array
	 * @param Sensor        $sensor
	 * @return TemperatureChartData
	 */
	public static function createFromArray(array $array, Sensor $sensor): TemperatureChartData {
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

		return new TemperatureChartData($sensor->getName(), $dates, $temperatures, $humidities);
	}
}