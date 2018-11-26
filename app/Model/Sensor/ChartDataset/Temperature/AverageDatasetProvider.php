<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ChartDataset\Temperature;


use Maisner\SmartHome\Model\Sensor\BasicDataProvider\Temperature\AverageValuesProvider;
use Maisner\SmartHome\Model\Sensor\ORM\Sensor;
use Nette\Utils\ArrayList;

class AverageDatasetProvider {

	/** @var AverageValuesProvider */
	private $averageValuesProvider;

	/**
	 * AverageDatasetProvider constructor.
	 * @param AverageValuesProvider $averageValuesProvider
	 */
	public function __construct(AverageValuesProvider $averageValuesProvider) {
		$this->averageValuesProvider = $averageValuesProvider;
	}

	/**
	 * @param ArrayList|Sensor[] $sensors
	 * @param \DateTimeImmutable $from
	 * @param \DateTimeImmutable $to
	 * @return Collection
	 * @throws \Doctrine\DBAL\DBALException
	 */
	public function getHourAverage(ArrayList $sensors, \DateTimeImmutable $from, \DateTimeImmutable $to): Collection {
		$collection = new Collection();

		foreach ($sensors as $sensor) {
			$dataset = Factory::createFromArray(
				$this->averageValuesProvider->getHourAverageValues($sensor->getId(), $from, $to),
				$sensor
			);

			$collection->add($dataset);
		}

		return $collection;
	}

}