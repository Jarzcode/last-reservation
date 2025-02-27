<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

final class AvailabilitySlotView
{
    public function __construct(
        public string $start,
        public string $end,
        public bool $available,
    ) {
    }
}
