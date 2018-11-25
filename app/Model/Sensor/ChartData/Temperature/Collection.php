<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor\ChartData\Temperature;


use Nette\Utils\ArrayList;

class Collection {

	/** @var ArrayList|Data[] */
	protected $list;

	public function __construct() {
		$this->list = new ArrayList();
	}

	/**
	 * @param Data $data
	 * @return Collection
	 */
	public function add(Data $data): self {
		$this->list[] = $data;

		return $this;
	}

	/**
	 * @return ArrayList|Data[]
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