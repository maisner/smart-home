<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ChartData;


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
}