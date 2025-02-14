<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Application\Query;

use LastReservation\Shared\Domain\Bus\Query;

final class SearchTables implements Query
{
    public function __construct(
        public string $restaurantId,
    ) {
    }
}