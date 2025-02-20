<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\Query;

final class NotificationView
{
    public function __construct(
        public readonly string $id,
        public readonly string $restaurantId,
        public readonly string $email,
        public readonly string $message,
        public readonly string $when
    ) {
    }
}
