<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\UseCase\Update;

use DateInterval;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;

final class PromoteReservationHandler
{
    public function __construct(private readonly ReservationRepository $repository)
    {
    }

    public function __invoke(PromoteReservation $command): void
    {
        $whiteListedReservation = $this->repository->findFirstWhiteListedByPartySize(
            restaurantId: $command->restaurantId,
            partySize: $command->partySize,
            startDate: $command->startDate,
        );

        if ($whiteListedReservation === null) {
            return;
        }

        $whiteListedReservation->changeTableId($command->tableId);
        $whiteListedReservation->setStartDate($command->startDate);
        $endDate = ReservationEndDate::create(
            $command->startDate->value()->add(new DateInterval(Reservation::RESERVATION_DURATION)),
            $command->startDate,
        );
        $whiteListedReservation->changeEndDate($endDate);
        $whiteListedReservation->pending();

        $this->repository->save($whiteListedReservation);
    }
}
