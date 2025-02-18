<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Query;

use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Shared\Domain\RestaurantId;

class SearchReservationsHandler
{
    public function __construct(
        private readonly ReservationRepository $repository,
        private readonly ReservationViewAssembler $assembler,
    ){
    }

    /** @return list<ReservationView> */
    public function __invoke(SearchReservations $query): array
    {
        $reservations = $this->repository->findAll(RestaurantId::create($query->restaurantId));

        return array_map(
            fn($reservation) => $this->assembler->invoke($reservation),
            $reservations
        );
    }
}
