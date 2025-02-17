<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\UseCase\Update;

use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationName;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Shared\Domain\Bus\Command;
use LastReservation\Shared\Domain\RestaurantId;

final class UpdateReservation implements Command
{
    public function __construct(
        public ReservationId $id,
        public RestaurantId $restaurantId,
        public ReservationName $name,
        public ReservationPartySize $partySize,
        public ReservationStartDate $start,
        public ReservationEndDate $end,
    ) {
    }
}
