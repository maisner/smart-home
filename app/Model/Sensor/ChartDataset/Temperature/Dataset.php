<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ChartDataset\Temperature;


class Dataset {

	/** @var string */
	private $sensorName;

	/** @var array|string[] */
	private $dates;

	/** @var array|string[]|null[] */
	private $temperatureValues;

	/** @var array|string[]|null[] */
	private $humidityValues;

	/**
	 * TemperatureChartData constructor.
	 * @param string                $sensorName
	 * @param array|string[]        $dates
	 * @param array|string[]|null[] $temperatureValues
	 * @param array|string[]|null[] $humidityValues
	 */
	public function __construct(string $sensorName, array $dates, array $temperatureValues, array $humidityValues) {
		$this->sensorName = $sensorName;
		$this->dates = $dates;
		$this->temperatureValues = $temperatureValues;
		$this->humidityValues = $humidityValues;
	}

	/**
	 * @return string
	 */
	public function getSensorName(): string {
		return $this->sensorName;
	}

	/**
	 * @return array|string[]
	 */
	public function getDates(): array {
		return $this->dates;
	}

	/**
	 * @return array|string[]|null[]
	 */
	public function getTemperatureValues(): array {
		return $this->temperatureValues;
	}

	/**
	 * @return array|string[]|null[]
	 */
	public function getHumidityValues(): array {
		return $this->humidityValues;
	}
}