<?php

declare(strict_types=1);

namespace App\Tests\Unit\Reservations\Reservation\Application\UseCase\Update;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Application\Service\AvailableTableSearcher;
use LastReservation\Reservations\Reservation\Application\UseCase\Update\UpdateReservation;
use LastReservation\Reservations\Reservation\Application\UseCase\Update\UpdateReservationHandler;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationName;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Reservation\Domain\Service\ReservationFinder;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\RestaurantId;
use PHPUnit\Framework\TestCase;

final class UpdateReservationHandlerTest extends TestCase
{
    public function test_fails_if_not_table_available(): void
    {
        $this->expectException(\Exception::class);

        $command = new UpdateReservation(
            id: ReservationId::generate(),
            restaurantId: RestaurantId::generate(),
            name: ReservationName::create('John Doe'),
            partySize: ReservationPartySize::create(2),
            start: ReservationStartDate::create(new DateTimeImmutable()),
            end: ReservationEndDate::create(new DateTimeImmutable(), new DateTimeImmutable()),
        );

        $finder = $this->createMock(ReservationFinder::class);
        $finder->method('invoke')->willReturn(Reservation::create(
            id: $command->id,
            restaurantId: $command->restaurantId,
            tableId: TableId::generate(),
            name: ReservationName::create('Another name'),
            startDate: ReservationStartDate::create(new DateTimeImmutable()),
            partySize: ReservationPartySize::create(2),
        ));

        $repository = $this->createMock(ReservationRepository::class);

        $searcher = $this->createMock(AvailableTableSearcher::class);

        $searcher->method('invoke')->willReturn(null);

        $handler = new UpdateReservationHandler(
            $finder,
            $repository,
            $searcher,
        );
    }

    public function test_update_a_reservation(): void
    {
        $now = new DateTimeImmutable();
        $startDate = ReservationStartDate::create($now);
        $command = new UpdateReservation(
            id: ReservationId::generate(),
            restaurantId: RestaurantId::generate(),
            name: ReservationName::create('John Doe'),
            partySize: ReservationPartySize::create(2),
            start: $startDate,
            end: ReservationEndDate::create($now->modify('+45 minutes'), $startDate),
        );

        $reservation = Reservation::create(
            id: $command->id,
            restaurantId: $command->restaurantId,
            tableId: TableId::generate(),
            name: ReservationName::create('Another name'),
            startDate: ReservationStartDate::create($now->modify('-1 hour')),
            partySize: $command->partySize,
        );

        $finder = $this->createMock(ReservationFinder::class);
        $finder->method('invoke')->willReturn($reservation);

        $repository = $this->createMock(ReservationRepository::class);

        $handler = new UpdateReservationHandler(
            $finder,
            $repository,
            $this->createMock(AvailableTableSearcher::class),
        );

        $handler($command);

        $this->assertEquals($reservation->name(), $command->name);
        $this->assertEquals($reservation->partySize(), $command->partySize);
        $this->assertEquals($reservation->startDate(), $command->start);
        $this->assertEquals($reservation->endDate(), $command->end);
    }
}
