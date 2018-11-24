<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Presenters;

use Maisner\SmartHome\Model\Sensor\SensorReader;


final class SensorReaderPresenter extends BasePresenter {

	/** @var SensorReader @inject */
	public $sensorReader;

	public function actionAll(): void {
		$this->sensorReader->readAll();

		$this->terminate();
	}
}
