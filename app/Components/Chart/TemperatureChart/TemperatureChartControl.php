<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Components\Chart\TemperatureChart;


use Maisner\SmartHome\Model\Sensor\ChartData\Temperature\Collection;
use Maisner\SmartHome\Model\Sensor\ChartData\Temperature\Data;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;

class TemperatureChartControl extends Control {

	/** @var Collection */
	private $dataCollection;

	public function __construct(Collection $dataCollection) {
		parent::__construct();

		$this->dataCollection = $dataCollection;
	}

	public function render(): void {
		/** @var Template $template */
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/default.latte');

		$template->chartData = $this->prepareAllData($this->dataCollection);

		$template->render();
	}

	/**
	 * @param Collection $dataCollection
	 * @return string
	 */
	private function prepareAllData(Collection $dataCollection): string {
		$res = [];

		foreach ($dataCollection->getAll() as $data) {
			$res[] = $this->prepareData($data);
		}

		return \GuzzleHttp\json_encode($res);
	}

	/**
	 * @param Data $data
	 * @return array|mixed[]
	 */
	private function prepareData(Data $data): array {
		$res = [];

		$res['name'] = $data->getSensorName();
		$res['dates'] = $data->getDates();
		$res['values'] = $data->getTemperatureValues();

		return $res;
	}

}