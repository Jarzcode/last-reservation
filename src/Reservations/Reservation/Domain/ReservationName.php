<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

final class ReservationName
{
    private const MAX_LENGTH = 500;

    private function __construct(public string $value)
    {
    }
    
    public static function create(string $value): self
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Reservation name cannot be empty');
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException('Reservation name cannot be longer than ' . self::MAX_LENGTH . ' characters');
        }
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
