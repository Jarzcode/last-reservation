<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain\Event;

use LastReservation\Shared\Domain\DomainEvent;

final class ReservationCancelled implements DomainEvent
{
    public function __construct(
        public string $id,
        public string $restaurantId,
        public string $tableId,
        public string $name,
        public int $partySize,
        public string $start,
        public string $end,
    ) {
    }
}
