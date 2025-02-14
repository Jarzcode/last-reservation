<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Application\Query;

final class TableView
{
    public function __construct(
        public string $id,
        public string $name,
        public int $capacity,
        public string $location,
    ) {
    }
} 