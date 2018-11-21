<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Presenters;

use Maisner\SmartHome\Model\Sensor\ChartData\TemperatureChartDataFactory;
use Maisner\SmartHome\Model\Sensor\ORM\SensorDataRepository;
use Maisner\SmartHome\Model\Sensor\ORM\SensorRepository;
use Maisner\SmartHome\Model\Sensor\SensorDataProvider;
use Maisner\SmartHome\Model\Sensor\SensorReader;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter {

	/** @var SensorReader @inject */
	public $sensorReader;

	/** @var SensorDataRepository @inject */
	public $sensorDataRepository;

	/** @var SensorRepository @inject */
	public $sensorRepository;

	/** @var SensorDataProvider @inject */
	public $sensorDataProvider;

	public function actionDefault(): void {
		$sensor = $this->sensorRepository->getById(1);

		//		$a = $this->sensorDataProvider->findByDay(new \DateTimeImmutable(), $sensor);
		//		\dump($a);

		//		$a = $this->sensorDataProvider->getDayAvarageValues($sensor->getId(), new \DateTimeImmutable('2018-11-10'), new \DateTimeImmutable('2018-11-20'));
		//		\dump($a);
		//		exit;

		$temperatureChartData = TemperatureChartDataFactory::create(
			$this->sensorDataRepository->findBySensor($sensor->getId())
		);
		$this->getTemplate()->data = $temperatureChartData;
	}

	public function actionReadSensors(): void {
		$this->sensorReader->readAll();

		$this->terminate();
	}
}
