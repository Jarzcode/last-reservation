<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use function Symfony\Component\Translation\t;

final class ReservationPhone
{
    private function __construct(private string $value)
    {
    }

    public static function create(string $value): self
    {
        if (!preg_match('/^\+?[0-9]{1,4}[0-9]{6,14}$/', $value)) {
            //TODO: Throw a custom domain exception
            throw new \InvalidArgumentException('Invalid phone number');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(ReservationPhone $phone): bool
    {
        return $this->value === $phone->value;
    }
}
