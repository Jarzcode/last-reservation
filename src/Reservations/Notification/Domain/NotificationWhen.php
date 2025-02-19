<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

use DateTimeImmutable;

final class NotificationWhen
{
    public function __construct(private readonly DateTimeImmutable $value)
    {
    }

    public static function create(DateTimeImmutable $value): self
    {
        return new self($value);
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }
}
