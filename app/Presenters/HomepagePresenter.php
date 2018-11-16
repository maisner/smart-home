<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Presenters;

use Maisner\SmartHome\Model\Sensor\ORM\SensorData;
use Maisner\SmartHome\Model\Sensor\ORM\SensorDataRepository;
use Maisner\SmartHome\Model\Sensor\SensorReader;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter {

	/** @var SensorReader @inject */
	public $sensorReader;

	/** @var SensorDataRepository @inject */
	public $sensorDataRepository;

	public function actionDefault(): void {
		$temperatures = [];
		$dates = [];
		/** @var SensorData $data */
		foreach ($this->sensorDataRepository->findBySensor(1) as $data) {
			$json = \GuzzleHttp\json_decode($data->getData());

			$dates[] = $data->getCreatedAt()->format('G:i');
			$temperatures[] = $json->temperature;
		}

		$this->getTemplate()->temperatures = $temperatures;
		$this->getTemplate()->dates = $dates;
	}

	public function actionReadSensors(): void {
		$this->sensorReader->readAll();

		$this->terminate();
	}
}
