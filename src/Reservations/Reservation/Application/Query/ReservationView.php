<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

final class ReservationView
{
    public function __construct(
        public string $id,
        public string $tableId,
        public string $restaurantId,
        public string $status,
        public string $name,
        public string $startDate,
        public string $endDate,
    ) {
    }
}
