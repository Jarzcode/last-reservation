<?php

declare(strict_types=1);

namespace LastReservation\Shared\Domain;

use Symfony\Component\Uid\Ulid; //I've decided to couple to this library in my domain

final class RestaurantId
{
    private function __construct(private string $value)
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

    public function equals(RestaurantId $restaurantId): bool
    {
        return $this->value() === $restaurantId->value();
    }
}
