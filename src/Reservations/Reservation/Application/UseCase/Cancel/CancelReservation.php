<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\UseCase\Cancel;

use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Shared\Domain\Bus\Command;
use LastReservation\Shared\Domain\RestaurantId;

final class CancelReservation implements Command
{
    public function __construct(
        public ReservationId $reservationId,
        public RestaurantId $restaurantId,
    ) {
    }
}