<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

final class TableDescription
{
    private const MAX_LENGTH = 2000;

    private function __construct(public string $value)
    {
    }

    public static function create(string $value): self
    {
        if (empty(trim($value))) {
            // TODO: Throw a custom DomainException
            throw new \InvalidArgumentException('Table description cannot be empty');
        }

        if (strlen($value) > self::MAX_LENGTH) {
            // TODO: Throw a custom DomainException
            throw new \InvalidArgumentException('Table description cannot be longer than ' . self::MAX_LENGTH . ' characters');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
