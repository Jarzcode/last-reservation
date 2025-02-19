<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

interface NotifiationRepository
{
    public function save(EmailNotification $notification): void;

    /** @return list<EmailNotification>  */
    public function findByDate(): array;
}
