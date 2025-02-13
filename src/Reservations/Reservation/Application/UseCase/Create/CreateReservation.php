<?php

namespace LastReservation\Reservations\Reservation\Application\UseCase\Create;

use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationName;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Shared\Domain\Bus\Command;
use LastReservation\Shared\Domain\RestaurantId;

final class CreateReservation implements Command
{
    public function __construct(
        public readonly RestaurantId $restaurantId,
        public readonly ReservationId $id,
        public readonly ReservationName $name,
        public readonly ReservationEmail $email,
        public readonly ReservationPhone $phone,
        public readonly ReservationStartDate $when,
        public readonly ReservationPartySize $partySize,
    ) {
    }
}