<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Domain;

use Symfony\Component\Uid\Ulid;

final class NotificationId
{
    public function __construct(private readonly string $value)
    {
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(
            (new Ulid())->__toString()
        );
    }

    public function value(): string
    {
        return $this->value;
    }
}
