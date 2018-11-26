<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Presenters;

use Maisner\SmartHome\Components\Chart\TemperatureChart\ITemperatureChartControlFactory;
use Maisner\SmartHome\Components\Chart\TemperatureChart\TemperatureChartControl;
use Maisner\SmartHome\Components\DateFilter\DateFilterControl;
use Maisner\SmartHome\Components\DateFilter\IDateFilterControlFactory;
use Maisner\SmartHome\Model\Sensor\BasicDataProvider\Temperature\AverageValuesProvider;
use Maisner\SmartHome\Model\Sensor\ChartDataset\Temperature\Collection;
use Maisner\SmartHome\Model\Sensor\ChartDataset\Temperature\Factory;
use Maisner\SmartHome\Model\Sensor\ORM\SensorRepository;


final class HomepagePresenter extends BasePresenter {

	public const DATE_FORMAT = 'Y-m-d';

	/** @var string @persistent */
	public $from;

	/** @var string @persistent */
	public $to;

	/** @var SensorRepository @inject */
	public $sensorRepository;

	/** @var AverageValuesProvider @inject */
	public $tempDataProvider;

	/** @var IDateFilterControlFactory @inject */
	public $dateFilterFactory;

	/** @var ITemperatureChartControlFactory @inject */
	public $temperatureChartFactory;

	/**
	 * @throws \Exception
	 */
	public function beforeRender(): void {
		parent::beforeRender();

		if ($this->from === NULL || $this->to === NULL) {
			$today = (new \DateTimeImmutable())->format(self::DATE_FORMAT);

			$this->from = $today;
			$this->to = $today;
		}
	}

	/**
	 * @return DateFilterControl
	 * @throws \Exception
	 */
	protected function createComponentDateFilter(): DateFilterControl {
		$defaultFrom = new \DateTimeImmutable($this->from);
		$defaultTo = new \DateTimeImmutable($this->to);

		$control = $this->dateFilterFactory->create($defaultFrom, $defaultTo);
		$control->onFilter[] = function (
			DateFilterControl $sender,
			\DateTimeImmutable $from,
			\DateTimeImmutable $to
		): void {
			$this->from = $from->format(self::DATE_FORMAT);
			$this->to = $to->format(self::DATE_FORMAT);

			if ($this->isAjax()) {
				$this->redrawControl('chart');

				return;
			}

			$this->redirect('this');
		};

		return $control;
	}

	/**
	 * @return TemperatureChartControl
	 * @throws \Doctrine\DBAL\DBALException
	 */
	protected function createComponentTemperatureChart(): TemperatureChartControl {
		$sensor = $this->sensorRepository->getById(1);

		$temperatureChartData = Factory::createFromArray(
			$this->tempDataProvider->getHourAverageValues(
				$sensor->getId(),
				new \DateTimeImmutable($this->from),
				new \DateTimeImmutable($this->to)
			),
			$sensor
		);

		$collection = new Collection();
		$collection->add($temperatureChartData);

		return $this->temperatureChartFactory->create($collection);
	}
}
