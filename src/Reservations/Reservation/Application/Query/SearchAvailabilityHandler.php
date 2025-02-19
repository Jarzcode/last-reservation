<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Application\Service\AvailabilitySlotsAssembler;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Table\Application\Query\SearchTables;
use LastReservation\Shared\Domain\Bus\QueryBus;
use LastReservation\Shared\Domain\RestaurantId;

final class SearchAvailabilityHandler
{
    public function __construct(
        private readonly ReservationRepository $repository,
        private readonly AvailabilitySlotsAssembler $availabilitySlotsAssembler,
        private readonly QueryBus $queryBus,
    ) {
    }

    /** @return list<TableAvailabilityView> */
    public function __invoke(SearchAvailability $query): array
    {
        $tableViews = $this->queryBus->ask(
            new SearchTables(
                restaurantId: $query->restaurantId,
                capacity: $query->partySize,
            ),
        );

        $tablesAvailability = [];
        foreach ($tableViews as $tableView) {
            $tableReservationsForTheDate = $this->repository->findReservationsByTableAndDate(
                tableId: $tableView->table->id,
                restaurantId: RestaurantId::create($query->restaurantId),
                date: new DateTimeImmutable($query->date),
            );

            $tablesAvailability[] = new TableAvailabilityView(
                table: $tableView,
                availabilitySlots: $this->availabilitySlotsAssembler->invoke($tableReservationsForTheDate),
            );
        }

        return $tablesAvailability;
    }
}
