<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Components\DateFilter;


interface IDateFilterControlFactory {

	/**
	 * @param \DateTimeImmutable $defaultFrom
	 * @param \DateTimeImmutable $defaultTo
	 * @return DateFilterControl
	 */
	public function create(\DateTimeImmutable $defaultFrom, \DateTimeImmutable $defaultTo): DateFilterControl;

}
