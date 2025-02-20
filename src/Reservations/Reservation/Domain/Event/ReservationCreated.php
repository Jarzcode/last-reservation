<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain\Event;

use DateTimeImmutable;
use LastReservation\Shared\Domain\DomainEvent;

final class ReservationCreated implements DomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly string $tableId,
        public readonly string $name,
        public readonly string $status,
        public readonly DateTimeImmutable $startDate,
        public readonly DateTimeImmutable $endDate,
    ) {
    }
} 