<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\UseCase\Update;

use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\Bus\Command;
use LastReservation\Shared\Domain\RestaurantId;

final class PromoteReservation implements Command
{
    public function __construct(
        public RestaurantId $restaurantId,
        public TableId $tableId,
        public ReservationPartySize $partySize,
        public ReservationStartDate $startDate,
    ) {
    }
}
