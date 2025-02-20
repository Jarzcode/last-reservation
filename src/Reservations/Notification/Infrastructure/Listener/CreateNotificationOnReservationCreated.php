<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Infrastructure\Listener;

use LastReservation\Reservations\Notification\Application\UseCase\Create\CreateNotification;
use LastReservation\Reservations\Notification\Domain\NotificationEmail;
use LastReservation\Reservations\Notification\Domain\NotificationId;
use LastReservation\Reservations\Notification\Domain\NotificationMessage;
use LastReservation\Reservations\Notification\Domain\NotificationWhen;
use LastReservation\Reservations\Reservation\Domain\Event\ReservationCreated;
use LastReservation\Shared\Domain\Bus\CommandBus;
use LastReservation\Shared\Domain\Bus\DomainEventListener;

final class CreateNotificationOnReservationCreated implements DomainEventListener
{
    private const STATUS_NEEDED = 'confirmed';

    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(ReservationCreated $event): void
    {
        if ($event->status !== self::STATUS_NEEDED) {
            return;
        }

        $this->commandBus->handle(
            new CreateNotification(
                id: NotificationId::generate(),
                email: NotificationEmail::create($event->name),
                message: NotificationMessage::create('Reservation ready!'),
                when: NotificationWhen::create($event->startDate),
            )
        );
    }
}
