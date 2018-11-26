<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ChartDataset\Temperature;


use Nette\Utils\ArrayList;

class Collection {

	/** @var ArrayList|Dataset[] */
	protected $list;

	public function __construct() {
		$this->list = new ArrayList();
	}

	/**
	 * @param Dataset $data
	 * @return Collection
	 */
	public function add(Dataset $data): self {
		$this->list[] = $data;

		return $this;
	}

	/**
	 * @return ArrayList|Dataset[]
	 */
	public function getAll(): ArrayList {
		return clone $this->list;
	}

	/**
	 * @return Collection
	 */
	public function clear(): self {
		$this->list = new ArrayList();

		return $this;
	}

}