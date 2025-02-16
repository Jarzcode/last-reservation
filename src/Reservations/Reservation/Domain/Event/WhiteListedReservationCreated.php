<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain\Event;

use DateTimeImmutable;
use LastReservation\Shared\Domain\DomainEvent;

final class WhiteListedReservationCreated implements DomainEvent
{
    public function __construct(
        public string $reservationId,
        public string $name,
        public DateTimeImmutable $startDate,
        public DateTimeImmutable $endDate,
    ) {
    }
}
