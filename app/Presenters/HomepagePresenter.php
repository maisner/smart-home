<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Presenters;

use Maisner\SmartHome\Components\Chart\TemperatureChart\ITemperatureChartControlFactory;
use Maisner\SmartHome\Components\Chart\TemperatureChart\TemperatureChartControl;
use Maisner\SmartHome\Components\DateFilter\DateFilterControl;
use Maisner\SmartHome\Components\DateFilter\IDateFilterControlFactory;
use Maisner\SmartHome\Model\Sensor\ChartDataset\Temperature\AverageDatasetProvider;
use Maisner\SmartHome\Model\Sensor\ORM\SensorRepository;
use Nette\Utils\ArrayList;


final class HomepagePresenter extends BasePresenter {

	public const DATE_FORMAT = 'Y-m-d';

	/** @var string @persistent */
	public $from;

	/** @var string @persistent */
	public $to;

	/** @var SensorRepository @inject */
	public $sensorRepository;

	/** @var IDateFilterControlFactory @inject */
	public $dateFilterFactory;

	/** @var ITemperatureChartControlFactory @inject */
	public $temperatureChartFactory;

	/** @var AverageDatasetProvider @inject */
	public $averageDatasetProvider;

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
		$sensorList = new ArrayList();
		$sensorList[] = $this->sensorRepository->getById(1);

		$collection = $this->averageDatasetProvider->getHourAverage(
			$sensorList,
			new \DateTimeImmutable($this->from),
			new \DateTimeImmutable($this->to)
		);

		return $this->temperatureChartFactory->create($collection);
	}
}
