<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

final class NotificationId
{
    public function __construct(private readonly string $value)
    {
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
