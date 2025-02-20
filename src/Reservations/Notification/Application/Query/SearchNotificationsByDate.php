<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\Query;

use LastReservation\Shared\Domain\Bus\Query;

final class SearchNotificationsByDate implements Query
{
    public function __construct(
        public readonly string $restaurantId,
        public readonly string $date
    ) {
    }
}
