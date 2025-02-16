<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\UseCase\Cancel;

use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\Service\ReservationFinder;

final class CancelReservationHandler
{
    public function __construct(
        private readonly ReservationFinder $finder,
        private readonly ReservationRepository $repository,
    ) {
    }

    public function __invoke(CancelReservation $command): void
    {
        $reservation = $this->finder->invoke(
            id: $command->reservationId,
            restaurantId: $command->restaurantId,
        );

        $reservation->cancel();

        $this->repository->save($reservation);

        $reservation->pullDomainEvents();
    }
}
