<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

final class ReservationName
{
    private function __construct(public string $value)
    {
    }
    
    public static function create(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
} 