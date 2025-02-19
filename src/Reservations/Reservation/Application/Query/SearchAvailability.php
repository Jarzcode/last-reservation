<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

use LastReservation\Shared\Domain\Bus\Query;

final class SearchAvailability implements Query
{
    public function __construct(
        public string $restaurantId,
        public int $partySize,
        public string $date,
    ) {
    }
}
