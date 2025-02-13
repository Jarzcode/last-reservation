<?php

namespace LastReservation\Reservations\Reservation\Application\UseCase\Create;

use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\Reservation;

final class CreateReservationHandler
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
    ) {
    }

    public function __invoke(CreateReservation $command): void
    {
        //TODO: Check availability and get the table id

        $reservation = Reservation::create(
            id: $command->id,
            restaurantId: $command->restaurantId,
            tableId: $tableId,
            name: $command->name,
            startDate: $command->when,
            partySize: $command->partySize,
        );

        $this->reservationRepository->save($reservation);
    }
}