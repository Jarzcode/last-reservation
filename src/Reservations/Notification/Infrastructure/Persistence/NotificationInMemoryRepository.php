<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Infrastructure\Persistence;

use LastReservation\Reservations\Notification\Domain\EmailNotification;
use LastReservation\Reservations\Notification\Domain\NotificationRepository;
use LastReservation\Reservations\Notification\Domain\NotificationWhen;

final class NotificationInMemoryRepository implements NotificationRepository
{
    private $notifications = [];

    public function save(EmailNotification $notification): void
    {
        $notifications[] = $notification;
    }

    public function findByDate(NotificationWhen $when): array
    {
        return array_filter($this->notifications, function (EmailNotification $notification) use ($when) {
            return
                $notification->when()->value()->format('Y-m-d H:i') ===
                $when->value()->format('Y-m-d H:i');
        });
    }
}
