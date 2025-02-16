<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use LastReservation\Reservations\Reservation\Domain\Event\ReservationCreated;
use LastReservation\Reservations\Reservation\Domain\Event\WhiteListedReservationCreated;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\AggregateRoot;
use LastReservation\Shared\Domain\RestaurantId;

class Reservation extends AggregateRoot
{
    private function __construct(
        private readonly ReservationId $id,
        private readonly RestaurantId $restaurantId,
        private readonly ?TableId $tableId,
        private readonly ReservationName $name,
        private readonly ReservationStartDate $startDate,
        private readonly ReservationEndDate $endDate,
        private ReservationStatus $status,
        private readonly ReservationPartySize $partySize,
    ) {
    }

    public static function create(
        ReservationId $id,
        RestaurantId $restaurantId,
        TableId $tableId,
        ReservationName $name,
        ReservationStartDate $startDate,
        ReservationPartySize $partySize,
    ): self {
        $endDate = $startDate->addMinutes(45);

        $reservation = new self(
            id: $id,
            restaurantId: $restaurantId,
            tableId: $tableId,
            name: $name,
            startDate: $startDate,
            endDate: ReservationEndDate::create(
                $endDate->value(),
                $startDate,
            ),
            status: $reservationStatus ?? ReservationStatus::COMPLETED,
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

    public static function createWhiteListed(
        ReservationId $id,
        RestaurantId $restaurantId,
        ReservationName $name,
        ReservationStartDate $startDate,
        ReservationPartySize $partySize,
    ): self {
        $endDate = ReservationEndDate::create(
            $startDate->value()->modify('+45 minutes'),
            $startDate,
        );

        $reservation = new self(
            id: $id,
            restaurantId: $restaurantId,
            tableId: null,
            name: $name,
            startDate: $startDate,
            endDate: $endDate,
            status: ReservationStatus::WHITELISTED,
            partySize: $partySize,
        );

        $reservation->record(
            new WhiteListedReservationCreated(
                reservationId: $id->value(),
                name: $name->value(),
                startDate: $startDate->value(),
                endDate: $endDate->value(),
            )
        );

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

    public function setStatus(ReservationStatus $status): void
    {
        $this->status = $status;
    }
}
