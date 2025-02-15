<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Service;

use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Table\Application\Query\SearchTables;
use LastReservation\Reservations\Table\Application\Query\TableView;
use LastReservation\Shared\Domain\Bus\QueryBus;
use LastReservation\Shared\Domain\RestaurantId;

final class AvailableTableSearcher
{
    public function __construct(
        private readonly ReservationRepository $repository,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function invoke(
        RestaurantId $restaurantId,
        ReservationPartySize $partySize,
        ReservationStartDate $when,
    ): ?TableView {
        $tableViews = $this->queryBus->ask(
            new SearchTables(
                restaurantId: $restaurantId->value(),
                capacity: $partySize->value(),
            ),
        );

        //TODO: For each table, check if there is another reservation for the same $where (45 min upper or down)
        foreach($tableViews as $tableView) {
            $reservation = $this->repository->findByTableAndAvailability(
                tableId: $tableView->id,
                startDate: $when,
            );

            if ($reservation !== null) {
                return $tableView;
            }
        }

        return null;
    }
}