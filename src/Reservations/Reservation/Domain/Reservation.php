<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use LastReservation\Shared\Domain\AggregateRoot;
use LastReservation\Reservations\Table\Domain\TableId;
use LastReservation\Reservations\Reservation\Domain\Event\ReservationCreated;

class Reservation extends AggregateRoot
{
    private function __construct(
        private readonly ReservationId $id,
        private readonly TableId $tableId,
        private readonly ReservationName $name,
        private readonly ReservationStartDate $startDate,
        private readonly ReservationEndDate $endDate,
    ) {
    }

    public static function create(
        ReservationId $id,
        TableId $tableId,
        ReservationName $name,
        ReservationStartDate $startDate,
        ReservationEndDate $endDate,
    ): self {
        $reservation = new self(
            $id,
            $tableId,
            $name,
            $startDate,
            $endDate,
        );

        $reservation->record(new ReservationCreated(
            $id->value(),
            $tableId->value(),
            $name->value(),
            $startDate->value(),
            $endDate->value()
        ));

        return $reservation;
    }
}
