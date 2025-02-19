<?php

declare(strict_types=1);

namespace LastReservation\Shared\Domain\Bus;

use LastReservation\Shared\Domain\DomainEvent;

interface DomainEventBus
{
    public function publish(DomainEvent ...$domainEvents): void;
}
