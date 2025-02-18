<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

use LastReservation\Reservations\Reservation\Domain\Reservation;

final class ReservationViewAssembler
{
    public function invoke(Reservation $reservation): ReservationView
    {
        return new ReservationView(
            id: $reservation->id()->value(),
            tableId: $reservation->tableId()->value(),
            restaurantId: $reservation->restaurantId()->value(),
            status: $reservation->status()->value(),
            name: $reservation->name()->value(),
            startDate: $reservation->startDate()->value()->format('Y-m-d H:i:s'),
            endDate: $reservation->endDate()->value()->format('Y-m-d H:i:s'),
        );
    }
}