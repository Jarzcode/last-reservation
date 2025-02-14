<?php

declare(strict_types=1);

namespace LastReservation\Tests\Unit\Reservations\Reservation\Application\UseCase\Create;

use LastReservation\Reservations\Reservation\Application\UseCase\Create\CreateReservationHandler;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Infrastructure\Persistence\ReservationInMemoryRepository;
use PHPUnit\Framework\TestCase;

final class CreateReservationHandlerTest extends TestCase
{
    private ReservationRepository $repository;
    private CreateReservationHandler $handler;

    protected function setUp(): void
    {
        $this->repository = new ReservationInMemoryRepository();
        $this->handler = new CreateReservationHandler(
            $this->repository,
        );
    }

    public function test_creates_a_completed_reservation_if_there_is_availability()
    {

    }

    public function test_set_a_reservation_as_whitliste_if_there_is_no_availability()
    {

    }
}