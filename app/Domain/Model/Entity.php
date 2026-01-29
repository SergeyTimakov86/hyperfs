<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Shared\Domain\Value\Id;
use JsonSerializable;

/**
 * Base for all Domain Entities.
 */
abstract class Entity implements JsonSerializable
{
    abstract public function asArray(): array;

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }

    abstract function id(): ?Id;
}
