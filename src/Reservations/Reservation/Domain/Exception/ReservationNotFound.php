<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain\Exception;

use Exception;
use LastReservation\Reservations\Reservation\Domain\ReservationId;

final class ReservationNotFound extends Exception
{
    public static function withId(ReservationId $id): self
    {
        return new self(
            sprintf('Reservation with id <%s> not found', $id->value())
        );
    }
}