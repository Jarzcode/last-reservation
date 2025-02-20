<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

use LastReservation\Shared\Domain\RestaurantId;

interface NotificationRepository
{
    public function save(EmailNotification $notification): void;

    /** @return list<EmailNotification>  */
    public function findByDate(RestaurantId $restaurantId, NotificationWhen $when): array;
}
