<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

use Symfony\Component\Uid\Ulid;

final class TableId {
    private function __construct(public string $value)
    {
    }
    
    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(
            (new Ulid())->__tostring()
        );
    }

    public function value(): string
    {
        return $this->value;
    }
}