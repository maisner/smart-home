<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Sensor;


use Maisner\Enum\AbstractEnum;

class TypeEnum extends AbstractEnum {

	public const TEMPERATURE_AND_HUMIDITY = 'temperature_and_humidity';

	/**
	 * @return array|string[]
	 */
	protected static function allowedValues(): array {
		return [self::TEMPERATURE_AND_HUMIDITY];
	}

	/**
	 * @return TypeEnum
	 */
	public static function TEMPERATURE_AND_HUMIDITY(): self {
		return new self(self::TEMPERATURE_AND_HUMIDITY);
	}
}