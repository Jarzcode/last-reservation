<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use LastReservation\Reservations\Reservation\Domain\Event\ReservationCancelled;
use LastReservation\Reservations\Reservation\Domain\Event\ReservationCreated;
use LastReservation\Reservations\Reservation\Domain\Event\WhiteListedReservationCreated;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\AggregateRoot;
use LastReservation\Shared\Domain\RestaurantId;

class Reservation extends AggregateRoot
{
    public const START_TIME = '13:00:00'; // It should come from the restaurant settings
    public const END_TIME = '23:00:00'; // It should come from the restaurant settings

    public function __construct(
        private readonly ReservationId $id,
        private readonly RestaurantId $restaurantId,
        private ?TableId $tableId,
        private ReservationName $name,
        private ReservationStartDate $startDate,
        private ReservationEndDate $endDate,
        private ReservationStatus $status,
        private ReservationPartySize $partySize,
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

    public function restaurantId(): RestaurantId
    {
        return $this->restaurantId;
    }

    public function tableId(): TableId
    {
        return $this->tableId;
    }

    public function changeTableId(TableId $tableId): void
    {
        $this->tableId = $tableId;

        //TODO: throw a domain event if we need to sync with other services
    }

    public function name(): ReservationName
    {
        return $this->name;
    }

    public function changeName(ReservationName $name): void
    {
        $this->name = $name;

        //TODO: throw a domain event if we need to sync with other services
    }

    public function startDate(): ReservationStartDate
    {
        return $this->startDate;
    }

    public function changeStartDate(ReservationStartDate $startDate): void
    {
        $this->startDate = $startDate;

        //TODO: throw a domain event if we need to sync with other services
    }

    public function endDate(): ReservationEndDate
    {
        return $this->endDate;
    }

    public function changeEndDate(ReservationEndDate $endDate): void
    {
        $this->endDate = $endDate;

        //TODO: throw a domain event if we need to sync with other services
    }

    public function status(): ReservationStatus
    {
        return $this->status;
    }

    public function partySize(): ReservationPartySize
    {
        return $this->partySize;
    }
    public function changePartySize(ReservationPartySize $partySize): void
    {
        $this->partySize = $partySize;

        //TODO: throw a domain event if we need to sync with other services
    }

    public function cancel(): void
    {
        $this->status = ReservationStatus::CANCELLED;

        $this->record(new ReservationCancelled(
            $this->id->value(),
            $this->tableId->value(),
            $this->name->value(),
            $this->startDate->value()->format('Y-m-d H:i:s'),
            $this->endDate->value()->format('Y-m-d H:i:s'),
        ));
    }
}
