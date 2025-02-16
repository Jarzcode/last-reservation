<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain\Service;

use LastReservation\Reservations\Reservation\Domain\Exception\ReservationNotFound;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Shared\Domain\RestaurantId;

class ReservationFinder
{
    public function __construct(
        private readonly ReservationRepository $repository
    ) {
    }

    public function invoke(ReservationId $id, RestaurantId $restaurantId): Reservation
    {
        return $this->repository->findById(
            id: $id,
            restaurantId: $restaurantId
        ) ?? throw ReservationNotFound::withId($id);
    }
}
