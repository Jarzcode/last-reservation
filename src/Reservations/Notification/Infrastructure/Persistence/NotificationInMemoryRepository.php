<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Infrastructure\Persistence;

use LastReservation\Reservations\Notification\Domain\EmailNotification;
use LastReservation\Reservations\Notification\Domain\NotificationRepository;
use LastReservation\Reservations\Notification\Domain\NotificationWhen;
use LastReservation\Shared\Domain\RestaurantId;

final class NotificationInMemoryRepository implements NotificationRepository
{
    private array $notifications = [];

    public function save(EmailNotification $notification): void
    {
        $this->notifications[] = $notification;
    }

    public function findByDate(RestaurantId $restaurantId, NotificationWhen $when): array
    {
        return array_filter(
            $this->notifications,
            function (EmailNotification $notification) use ($when, $restaurantId) {
                return
                    $notification->restaurantId()->value() === $restaurantId->value() &&
                    $notification->when()->value()->format('Y-m-d H:i') ===
                    $when->value()->format('Y-m-d H:i');
            });
    }
}
