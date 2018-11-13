<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model;


use Doctrine\ORM\Mapping as ORM;
use Maisner\SmartHome\Model\Utils\BaseEntity;

/**
 * @ORM\Entity
 */
class Sensor extends BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=FALSE)
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
	 * @param string             $type
	 * @param string             $url
	 */
	public function __construct(\DateTimeImmutable $createdAt, string $type, string $url) {
		$this->createdAt = $createdAt;
		$this->type = $type;
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
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