<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

use LastReservation\Shared\Domain\Bus\Query;

final class SearchReservations implements Query
{
    public function __construct(
        public string $restaurantId,
    ) {
    }
}