<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ORM;


use Doctrine\ORM\Mapping as ORM;
use Maisner\SmartHome\Model\Sensor\TypeEnum;
use Maisner\SmartHome\Model\Utils\BaseEntity;

/**
 * @ORM\Entity
 */
class Sensor extends BaseEntity {

	/**
	 * @var SensorGroup
	 * @ORM\ManyToOne(targetEntity="\Maisner\SmartHome\Model\Sensor\ORM\SensorGroup")
	 */
	protected $group;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=FALSE)
	 */
	protected $name;

	/**
	 * @var TypeEnum
	 * @ORM\Column(type="sensor_type_enum", nullable=FALSE)
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=FALSE)
	 */
	protected $url;

	/**
	 * Read sensor data interval in seconds
	 *
	 * @var int
	 * @ORM\Column(type="integer", nullable=FALSE)
	 */
	protected $readInterval = 60;

	/**
	 * Sensor constructor.
	 * @param \DateTimeImmutable $createdAt
	 * @param SensorGroup        $group
	 * @param string             $name
	 * @param TypeEnum           $type
	 * @param string             $url
	 */
	public function __construct(
		\DateTimeImmutable $createdAt,
		SensorGroup $group,
		string $name,
		TypeEnum $type,
		string $url
	) {
		parent::__construct($createdAt);

		$this->group = $group;
		$this->name = $name;
		$this->type = $type;
		$this->url = $url;
	}

	/**
	 * @return SensorGroup
	 */
	public function getGroup(): SensorGroup {
		return $this->group;
	}

	/**
	 * @param SensorGroup $group
	 */
	public function setGroup(SensorGroup $group): void {
		$this->group = $group;
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

	/**
	 * @return TypeEnum
	 */
	public function getType(): TypeEnum {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl(string $url): void {
		$this->url = $url;
	}

	/**
	 * @return int
	 */
	public function getReadInterval(): int {
		return $this->readInterval;
	}

	/**
	 * @param int $readInterval
	 */
	public function setReadInterval(int $readInterval): void {
		$this->readInterval = $readInterval;
	}
}