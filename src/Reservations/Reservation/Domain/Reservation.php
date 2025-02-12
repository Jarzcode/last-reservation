<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use LastReservation\Shared\Domain\AggregateRoot;
use LastReservation\Shared\Domain\RestaurantId;
use LastReservation\Reservations\Table\Domain\TableId;
use LastReservation\Reservations\Reservation\Domain\Event\ReservationCreated;

class Reservation extends AggregateRoot
{
    private function __construct(
        private readonly ReservationId $id,
        private readonly RestaurantId $restaurantId,
        private readonly TableId $tableId,
        private readonly ReservationName $name,
        private readonly ReservationStartDate $startDate,
        private readonly ReservationEndDate $endDate,
        private readonly ReservationStatus $status,
        private readonly ReservationPartySize $partySize,
    ) {
    }

    public static function create(
        ReservationId $id,
        RestaurantId $restaurantId,
        TableId $tableId,
        ReservationName $name,
        ReservationStartDate $startDate,
        ReservationEndDate $endDate,
        ReservationPartySize $partySize,
    ): self {
        $reservation = new self(
            id: $id,
            restaurantId: $restaurantId,
            tableId: $tableId,
            name: $name,
            startDate: $startDate,
            endDate: $endDate,
            status: ReservationStatus::PENDING,
            partySize: $partySize,
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

    public function id(): ReservationId
    {
        return $this->id;
    }

    public function tableId(): TableId
    {
        return $this->tableId;
    }

    public function name(): ReservationName
    {
        return $this->name;
    }
    
    public function startDate(): ReservationStartDate
    {
        return $this->startDate;
    }

    public function endDate(): ReservationEndDate
    {
        return $this->endDate;
    }

    public function status(): ReservationStatus
    {
        return $this->status;
    }
}
