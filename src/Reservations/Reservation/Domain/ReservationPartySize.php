<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

final class ReservationPartySize
{
    private const MIN_SIZE = 1;
    private const MAX_SIZE = 20;

    private function __construct(private int $value)
    {
    }
    
    public static function create(int $value): self
    {
        if ($value < self::MIN_SIZE || $value > self::MAX_SIZE) {
            // TODO: Use a custom exception
            throw new \InvalidArgumentException(
                sprintf('Party size must be between %d and %d people', self::MIN_SIZE, self::MAX_SIZE)
            );
        }
        
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(ReservationPartySize $partySize): bool
    {
        return $this->value === $partySize->value;
    }
}
