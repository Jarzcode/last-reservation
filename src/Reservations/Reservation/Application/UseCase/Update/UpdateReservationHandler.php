<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\UseCase\Update;

use Exception;
use LastReservation\Reservations\Reservation\Application\Service\AvailableTableSearcher;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\Service\ReservationFinder;
use LastReservation\Reservations\Shared\TableId;

class UpdateReservationHandler
{
    public function __construct(
        private readonly ReservationFinder $finder,
        private readonly ReservationRepository $repository,
        private readonly AvailableTableSearcher $availableTableSearcher,
    ) {
    }

    public function __invoke(UpdateReservation $command): void
    {
        $reservation = $this->finder->invoke(
            id: $command->id,
            restaurantId: $command->restaurantId,
        );

        $tableId = $this->checkAvailability($command, $reservation);

        /**
         * I do it this way because it more appropriate in case we have a distribute system and we
         * need to sync the information between the services.
         */
        $reservation->changeName($command->name);
        $reservation->changePartySize($command->partySize);
        $reservation->changeStartDate($command->start);
        $reservation->changeEndDate($command->end);
        $reservation->changeTableId($tableId);

        $this->repository->save($reservation);

        $reservation->pullDomainEvents();
    }

    private function checkAvailability(UpdateReservation $command, Reservation $reservation): TableId
    {
        if (
            $reservation->partySize()->value() === $command->partySize->value() &&
            $reservation->startDate()->value() === $command->start->value() &&
            $reservation->endDate()->value() === $command->end->value()
        ) {
            return $reservation->tableId();
        }

        $tableView = $this->availableTableSearcher->invoke(
            restaurantId: $command->restaurantId,
            partySize: $command->partySize,
            when: $command->start,
        );

        if ($tableView === null) {
            //TODO: Throw a custom exception
            throw new Exception('No available tables for the given party size and date');
        }

        return TableId::create($tableView->id);
    }
}
