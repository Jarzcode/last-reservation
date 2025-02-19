<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

use LastReservation\Reservations\Table\Application\Query\TableView;

final class TableAvailabilityView
{
    /** @param list<AvailabilityPeriodView> $availabilitySlots */
    public function __construct(
        public TableView $table,
        public array $availabilitySlots,
    ) {
    }
}
