<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use DateTimeImmutable;

final class ReservationStartDate
{
    private function __construct(private DateTimeImmutable $value)
    {
    }
    
    public static function create(DateTimeImmutable $value): self
    {
        if ($value < new DateTimeImmutable()) {
            throw new \InvalidArgumentException('Reservation start date cannot be in the past');
        }
        
        return new self($value);
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }
} 