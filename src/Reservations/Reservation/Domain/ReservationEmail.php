<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

final class ReservationEmail
{
    private function __construct(private string $value)
    {
    }

    public static function create(string $value): self
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            //TODO: Throw a custom domain exception
            throw new \InvalidArgumentException('Invalid email');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(ReservationEmail $email): bool
    {
        return $this->value === $email->value;
    }
}