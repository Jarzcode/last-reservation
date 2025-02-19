<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Infrastructure\Listener;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Application\UseCase\Update\PromoteReservation;
use LastReservation\Reservations\Reservation\Domain\Event\ReservationCancelled;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\Bus\CommandBus;
use LastReservation\Shared\Domain\Bus\DomainEventListener;
use LastReservation\Shared\Domain\RestaurantId;

final class PromoteReservationOnReservationCancelled implements DomainEventListener
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(ReservationCancelled $event): void
    {
        $this->commandBus->handle(
            new PromoteReservation(
                restaurantId: RestaurantId::create($event->restaurantId),
                tableId: TableId::create($event->tableId),
                partySize: ReservationPartySize::create($event->partySize),
                startDate: ReservationStartDate::create(new DateTimeImmutable($event->start)),
            )
        );
    }
}
