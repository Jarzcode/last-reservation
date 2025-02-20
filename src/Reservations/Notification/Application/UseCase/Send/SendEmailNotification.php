<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\UseCase\Send;

use LastReservation\Reservations\Notification\Domain\NotificationEmail;
use LastReservation\Reservations\Notification\Domain\NotificationMessage;
use LastReservation\Reservations\Notification\Domain\NotificationWhen;
use LastReservation\Shared\Domain\Bus\Command;
use LastReservation\Shared\Domain\RestaurantId;

final class SendEmailNotification implements Command
{
    public function __construct(
        public readonly RestaurantId $restaurantId,
        public readonly NotificationEmail $email,
        public readonly NotificationMessage $message,
        public readonly NotificationWhen $when
    ) {
    }
}
