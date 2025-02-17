<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Service;

use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Reservations\Table\Application\Query\SearchTables;
use LastReservation\Reservations\Table\Application\Query\TableView;
use LastReservation\Shared\Domain\Bus\QueryBus;
use LastReservation\Shared\Domain\RestaurantId;

class AvailableTableSearcher
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

        foreach($tableViews as $tableView) {
            $reservation = $this->repository->existsReservationForTheTableAndTime(
                tableId: TableId::create($tableView->id),
                starts: $when,
                ends: ReservationEndDate::create(
                    $when->addMinutes(ReservationStartDate::RESERVATION_DURATION)->value(),
                    $when,
                ),
            );

            if (!$reservation) {
                return $tableView;
            }
        }

        return null;
    }
}