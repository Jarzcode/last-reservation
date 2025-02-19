<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

use LastReservation\Shared\Domain\AggregateRoot;

final class EmailNotification extends AggregateRoot
{
    public function __construct(
        private readonly NotificationEmail $email,
        private readonly NotificationMessage $message,
        private readonly NotificationWhen $when
    ) {
    }

    public function email(): NotificationEmail
    {
        return $this->email;
    }

    public function message(): NotificationMessage
    {
        return $this->message;
    }

    public function when(): NotificationWhen
    {
        return $this->when;
    }
}
