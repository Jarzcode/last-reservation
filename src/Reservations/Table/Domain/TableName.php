<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

final class TableName
{
    private const MAX_LENGTH = 100;
    private function __construct(public string $value)
    {
    }

    public static function create(string $value): self
    {
        if (empty(trim($value))) {
            // TODO: Throw a custom DomainException

            throw new \InvalidArgumentException('Table name cannot be empty');
        }

        if (strlen($value) > self::MAX_LENGTH) {
            // TODO: Throw a custom DomainException
            throw new \InvalidArgumentException('Table name cannot be longer than ' . self::MAX_LENGTH . ' characters');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
