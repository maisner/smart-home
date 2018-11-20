<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Model\Utils\Exceptions;


class EntityNotFoundException extends \RuntimeException {

	public function __construct(string $entityClassName, int $entityId) {
		parent::__construct(\sprintf('Entity "%s" with id "%s" not found', $entityClassName, $entityId));
	}
}