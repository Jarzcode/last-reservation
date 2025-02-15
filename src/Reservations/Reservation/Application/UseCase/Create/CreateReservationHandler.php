<?php

namespace LastReservation\Reservations\Reservation\Application\UseCase\Create;

use LastReservation\Reservations\Reservation\Application\Service\AvailableTableSearcher;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationStatus;
use LastReservation\Reservations\Table\Domain\TableId;

final class CreateReservationHandler
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly AvailableTableSearcher $availableTableSearcher,
    ) {
    }

    public function __invoke(CreateReservation $command): void
    {
        $tableView = $this->availableTableSearcher->invoke(
            restaurantId: $command->restaurantId,
            partySize: $command->partySize,
            when: $command->when,
        );

        $reservation = Reservation::create(
            id: $command->id,
            restaurantId: $command->restaurantId,
            tableId: TableId::create($tableView->id),
            name: $command->name,
            startDate: $command->when,
            partySize: $command->partySize,
        );

        if ($tableView === null) {
            $reservation->setStatus(ReservationStatus::WHITELISTED);
        }

        $this->reservationRepository->save($reservation);
    }
}