<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Presenters;

use Maisner\SmartHome\Model\Sensor\ChartData\TemperatureChartDataFactory;
use Maisner\SmartHome\Model\Sensor\ORM\SensorDataRepository;
use Maisner\SmartHome\Model\Sensor\ORM\SensorRepository;
use Maisner\SmartHome\Model\Sensor\SensorDataProvider;
use Maisner\SmartHome\Model\Sensor\SensorReader;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;


final class HomepagePresenter extends BasePresenter {

	public const DATE_FORMAT = 'Y-m-d';

	/** @var string @persistent */
	public $from;

	/** @var string @persistent */
	public $to;

	/** @var SensorReader @inject */
	public $sensorReader;

	/** @var SensorDataRepository @inject */
	public $sensorDataRepository;

	/** @var SensorRepository @inject */
	public $sensorRepository;

	/** @var SensorDataProvider @inject */
	public $sensorDataProvider;

	public function beforeRender(): void {
		parent::beforeRender();

		$today = (new \DateTimeImmutable())->format(self::DATE_FORMAT);

		if ($this->from === NULL || $this->to === NULL) {
			$this->from = $today;
			$this->to = $today;
		}
	}

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

	public function actionReadSensors(): void {
		$this->sensorReader->readAll();

		$this->terminate();
	}

	/**
	 * @return Form
	 * @throws \Exception
	 */
	protected function createComponentFilterDate(): Form {
		$form = new Form();
		$form->addText('from', 'Od')
			->setDefaultValue($this->from)
			->setType('date')
			->setRequired();

		$form->addText('to', 'Do')
			->setDefaultValue($this->to)
			->setType('date')
			->setRequired();

		$form->addSubmit('submit', 'Odeslat');

		$form->onSuccess[] = function (Form $form, ArrayHash $values): void {
			$this->from = $values->from;
			$this->to = $values->to;

			if ($this->isAjax()) {
				$this->redrawControl('chart');

				return;
			}

			$this->redirect('this');
		};

		return $form;
	}
}
