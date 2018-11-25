<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Components\Chart\TemperatureChart;


use Maisner\SmartHome\Model\Sensor\ChartData\Temperature\Collection;

interface ITemperatureChartControlFactory {

	public function create(Collection $dataCollection): TemperatureChartControl;

}
