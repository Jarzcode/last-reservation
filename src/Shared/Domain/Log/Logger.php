<?php

declare(strict_types=1);

namespace LastReservation\Shared\Domain\Log;

interface Logger
{
    public function info(string $message, array $data = []);

    public function debug(string $message, array $data = []);

    public function error(string $message, array $data = []);
}