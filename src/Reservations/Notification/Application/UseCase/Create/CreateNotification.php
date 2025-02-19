<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\UseCase\Create;

use LastReservation\Reservations\Notification\Domain\NotificationEmail;
use LastReservation\Reservations\Notification\Domain\NotificationId;
use LastReservation\Reservations\Notification\Domain\NotificationMessage;
use LastReservation\Reservations\Notification\Domain\NotificationWhen;
use LastReservation\Shared\Domain\Bus\Command;

final class CreateNotification implements Command
{
    public function __construct(
        public readonly NotificationId $id,
        public readonly NotificationEmail $email,
        public readonly NotificationMessage $message,
        public readonly NotificationWhen $when
    ) {
    }
}
