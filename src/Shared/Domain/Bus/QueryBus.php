<?php

declare(strict_types=1);

namespace LastReservation\Shared\Domain\Bus;

interface QueryBus
{
    public function ask(Query $query);
}