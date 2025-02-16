<?php

declare(strict_types=1);

namespace App\Tests\Unit\Reservations\Reservation\Application\UseCase\Cancel;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Application\UseCase\Cancel\CancelReservation;
use LastReservation\Reservations\Reservation\Application\UseCase\Cancel\CancelReservationHandler;
use LastReservation\Reservations\Reservation\Domain\Exception\ReservationNotFound;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationName;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Reservation\Domain\ReservationStatus;
use LastReservation\Reservations\Reservation\Domain\Service\ReservationFinder;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\RestaurantId;
use Monolog\Test\TestCase;

final class CancelReservationHandlerTest extends TestCase
{
    public function test_fails_if_reservation_is_not_found(): void
    {
        $command = new CancelReservation(
            reservationId: ReservationId::generate(),
            restaurantId: RestaurantId::generate(),
        );

        $finder = $this->createMock(ReservationFinder::class);
        $finder->method('invoke')->willThrowException(ReservationNotFound::withId($command->reservationId));

        $repository = $this->createMock(ReservationRepository::class);

        $handler = new CancelReservationHandler($finder, $repository);

        $this->expectException(ReservationNotFound::class);
        $handler($command);
    }

    public function test_cancel_a_reservation(): void
    {
        $command = new CancelReservation(
            reservationId: ReservationId::generate(),
            restaurantId: RestaurantId::generate(),
        );

        $reservation = Reservation::create(
            id: $command->reservationId,
            restaurantId: $command->restaurantId,
            tableId: TableId::generate(),
            name: ReservationName::create('John Doe'),
            startDate: ReservationStartDate::create(new DateTimeImmutable()),
            partySize: ReservationPartySize::create(2),
        );

        $finder = $this->createMock(ReservationFinder::class);
        $finder->method('invoke')->willReturn($reservation);

        $repository = $this->createMock(ReservationRepository::class);
        $repository->expects($this->once())->method('save')->with($reservation);

        $handler = new CancelReservationHandler($finder, $repository);

        $handler($command);

        $this->assertEquals(ReservationStatus::CANCELLED, $reservation->status());
    }
}
