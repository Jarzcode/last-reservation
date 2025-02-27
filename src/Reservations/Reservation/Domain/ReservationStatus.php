<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

enum ReservationStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case WHITELISTED = 'whitelisted';

    public function value(): string
    {
        return $this->value;
    }

    public function isWhiteListed(): bool
    {
        return $this->value === self::WHITELISTED->value();
    }
}
