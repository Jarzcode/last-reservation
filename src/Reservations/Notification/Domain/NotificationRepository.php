<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

interface NotificationRepository
{
    public function save(EmailNotification $notification): void;

    /** @return list<EmailNotification>  */
    public function findByDate(NotificationWhen $when): array;
}
