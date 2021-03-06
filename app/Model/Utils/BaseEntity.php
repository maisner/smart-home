<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Utils;


use Doctrine\ORM\Mapping as ORM;
use Nette\NotImplementedException;

abstract class BaseEntity {

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=FALSE)
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @var \DateTimeImmutable
	 * @ORM\Column(type="datetime_immutable", nullable=FALSE)
	 */
	protected $createdAt;

	/**
	 * @var \DateTimeImmutable|null
	 * @ORM\Column(type="datetime_immutable", nullable=TRUE)
	 */
	protected $updatedAt;

	/**
	 * BaseEntity constructor.
	 * @param \DateTimeImmutable $createdAt
	 */
	public function __construct(\DateTimeImmutable $createdAt) {
		$this->createdAt = $createdAt;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	public function __clone() {
		throw new NotImplementedException();
	}

	/**
	 * @return \DateTimeImmutable
	 */
	public function getCreatedAt(): \DateTimeImmutable {
		return $this->createdAt;
	}

	/**
	 * @return \DateTimeImmutable|null
	 */
	public function getUpdatedAt(): ?\DateTimeImmutable {
		return $this->updatedAt;
	}

	/**
	 * @param \DateTimeImmutable|null $updatedAt
	 */
	public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void {
		$this->updatedAt = $updatedAt;
	}
}