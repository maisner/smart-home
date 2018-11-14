<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ORM;


use Doctrine\ORM\Mapping as ORM;
use Maisner\SmartHome\Model\Utils\BaseEntity;

/**
 * @ORM\Entity
 */
class SensorData extends BaseEntity {

	/**
	 * @var Sensor
	 * @ORM\ManyToOne(targetEntity="\Maisner\SmartHome\Model\Sensor\ORM\Sensor")
	 */
	protected $sensor;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=FALSE)
	 */
	protected $data;

	/**
	 * SensorData constructor.
	 * @param \DateTimeImmutable $createdAt
	 * @param Sensor             $sensor
	 * @param string             $data
	 */
	public function __construct(\DateTimeImmutable $createdAt, Sensor $sensor, string $data) {
		$this->createdAt = $createdAt;
		$this->sensor = $sensor;
		$this->data = $data;
	}

	/**
	 * @return Sensor
	 */
	public function getSensor(): Sensor {
		return $this->sensor;
	}

	/**
	 * @return string
	 */
	public function getData(): string {
		return $this->data;
	}
}