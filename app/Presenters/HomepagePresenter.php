<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Presenters;

use Maisner\SmartHome\Components\DateFilter\DateFilterControl;
use Maisner\SmartHome\Components\DateFilter\IDateFilterControlFactory;
use Maisner\SmartHome\Model\Sensor\ChartData\TemperatureChartDataFactory;
use Maisner\SmartHome\Model\Sensor\ORM\SensorRepository;
use Maisner\SmartHome\Model\Sensor\SensorDataProvider;


final class HomepagePresenter extends BasePresenter {

	public const DATE_FORMAT = 'Y-m-d';

	/** @var string @persistent */
	public $from;

	/** @var string @persistent */
	public $to;

	/** @var SensorRepository @inject */
	public $sensorRepository;

	/** @var SensorDataProvider @inject */
	public $sensorDataProvider;

	/** @var IDateFilterControlFactory @inject */
	public $dateFilterFactory;

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
	 * @throws \Doctrine\DBAL\DBALException
	 * @throws \Exception
	 */
	public function renderDefault(): void {
		$sensor = $this->sensorRepository->getById(1);

		$temperatureChartData = TemperatureChartDataFactory::createFromArray(
			$this->sensorDataProvider->getHourAverageValues(
				$sensor->getId(),
				new \DateTimeImmutable($this->from),
				new \DateTimeImmutable($this->to)
			),
			$sensor
		);

		$this->getTemplate()->data = $temperatureChartData;
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
}
