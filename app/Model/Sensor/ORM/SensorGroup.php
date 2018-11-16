<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ORM;


use Doctrine\ORM\Mapping as ORM;
use Maisner\SmartHome\Model\Utils\BaseEntity;

/**
 * @ORM\Entity
 */
class SensorGroup extends BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=FALSE)
	 */
	protected $name;

	/**
	 * SensorGroup constructor.
	 * @param \DateTimeImmutable $createdAt
	 * @param string             $name
	 */
	public function __construct(\DateTimeImmutable $createdAt, string $name) {
		parent::__construct($createdAt);

		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
}