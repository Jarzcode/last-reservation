<?php

declare(strict_types=1);

namespace LastReservation\Shared\Domain\Bus;

use LastReservation\User\Domain\Event\DomainEvent;

interface DomainEventBus
{
    public function publish(DomainEvent ...$domainEvents): void;
}