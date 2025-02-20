<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\Query;

use LastReservation\Reservations\Notification\Domain\EmailNotification;

final class NotificationViewAssembler
{
    public function invoke(EmailNotification $notification): NotificationView
    {
        return new NotificationView(
            id: $notification->id()->value(),
            restaurantId: $notification->restaurantId()->value(),
            email: $notification->email()->value(),
            message: $notification->message()->value(),
            when: $notification->when()->value()->format('Y-m-d H:i:s'),
        );
    }
}
