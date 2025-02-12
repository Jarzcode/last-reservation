<?php

declare(strict_types=1);

namespace LastReservation\Shared\Domain\Bus;

interface CommandBus
{
    public function handle(Command $command);
}