<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use DateTimeImmutable;

final class ReservationEndDate
{
    private function __construct(private DateTimeImmutable $value)
    {
    }
    
    public static function create(DateTimeImmutable $value, ReservationStartDate $startDate): self
    {
        if ($value <= $startDate->value()) {
            throw new \InvalidArgumentException('Reservation end date must be after start date');
        }
        
        return new self($value);
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }
} 