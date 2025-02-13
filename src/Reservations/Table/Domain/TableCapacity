<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

final class TableCapacity
{
    private const MAX_CAPACITY = 10;
    private function __construct(public int $value)
    {
    }

    public static function create(int $value): self
    {
        if ($value <= 0) {
            // TODO: Throw a custom DomainException
            throw new \InvalidArgumentException('Table capacity must be greater than 0');
        }

        if ($value > self::MAX_CAPACITY) {
            // TODO: Throw a custom DomainException
            throw new \InvalidArgumentException('Table capacity cannot be greater than ' . self::MAX_CAPACITY);
        }

        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
