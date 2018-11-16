<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Utils\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Maisner\SmartHome\Model\Sensor\TypeEnum;

class SensorTypeEnumType extends Type {

	public const SENSOR_TYPE_ENUM = 'sensor_type_enum';

	/**
	 * @return string
	 */
	public function getName(): string {
		return self::SENSOR_TYPE_ENUM;
	}

	/**
	 * @param array|mixed[]    $fieldDeclaration
	 * @param AbstractPlatform $platform
	 * @return string
	 */
	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string {
		return 'VARCHAR (100)';
	}

	/**
	 * @param mixed            $value
	 * @param AbstractPlatform $platform
	 * @return TypeEnum
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform): TypeEnum {
		return new TypeEnum($value);
	}

	/**
	 * @param mixed            $value
	 * @param AbstractPlatform $platform
	 * @return mixed|string
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform) {
		if ($value instanceof TypeEnum) {
			return (string)$value;
		}

		return $value;
	}
}