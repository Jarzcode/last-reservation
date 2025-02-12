<?php

declare(strict_types=1);

namespace LastReservation\User\Domain;

use LastReservation\User\Domain\Event\DomainEvent;

/**
 * This class could be in a "shared" bounded context
 */
abstract class AggregateRoot
{
    private array $domainEvents = [];

    /**
     * @return DomainEvent[]
     */
    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    // DomainEvent would be a Domain class in the Shared bounded context
    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}