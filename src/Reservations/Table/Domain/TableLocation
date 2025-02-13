<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

final class TableLocation
{
    private function __construct(public string $value)
    {
    }

    public static function create(string $value): self
    {
        if (empty(trim($value))) {
            // TODO: Throw a custom DomainException
            throw new \InvalidArgumentException('Table location cannot be empty');
        }

        if (strlen($value) > 1000) {
            // TODO: Throw a custom DomainException
            throw new \InvalidArgumentException('Table location cannot be longer than 1000 characters');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
