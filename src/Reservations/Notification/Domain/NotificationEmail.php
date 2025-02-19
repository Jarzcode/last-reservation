<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

use InvalidArgumentException;

final class NotificationEmail
{
    public function __construct(private string $value)
    {
    }

    public static function create(string $value): self
    {
        self::validate($value);

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private static function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address: $value");
        }
    }
}
