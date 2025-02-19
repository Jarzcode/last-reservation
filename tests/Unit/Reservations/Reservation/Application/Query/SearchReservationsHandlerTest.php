<?php

declare(strict_types=1);

namespace App\Tests\Unit\Reservations\Reservation\Application\Query;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Application\Query\ReservationView;
use LastReservation\Reservations\Reservation\Application\Query\ReservationViewAssembler;
use LastReservation\Reservations\Reservation\Application\Query\SearchReservations;
use LastReservation\Reservations\Reservation\Application\Query\SearchReservationsHandler;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationName;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Reservation\Domain\ReservationStatus;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\RestaurantId;
use Monolog\Test\TestCase;

class SearchReservationsHandlerTest extends TestCase
{
    public function test_returns_list_of_reservation_views(): void
    {
        $restaurantId = RestaurantId::generate();
        $startDate = ReservationStartDate::create(new DateTimeImmutable('2021-10-10 10:00:00'));
        $query = new SearchReservations($restaurantId->value());
        $reservation = new Reservation(
            id: ReservationId::generate(),
            restaurantId: $restaurantId,
            tableId: TableId::generate(),
            name: ReservationName::create('John Doe'),
            startDate: $startDate,
            endDate: ReservationEndDate::create(new DateTimeImmutable('2021-10-10 10:45:00'), $startDate),
            status: ReservationStatus::COMPLETED,
            partySize: ReservationPartySize::create(2),
        );

        $reservationRepository = $this->createMock(ReservationRepository::class);
        $reservationRepository->expects($this->once())
            ->method('findAll')
            ->with(RestaurantId::create('restaurant-id'))
            ->willReturn([$reservation]);
        $handler = new SearchReservationsHandler($reservationRepository, new ReservationViewAssembler());

        $result = $handler($query);

        $this->assertCount(1, $result);
        $this->assertEquals(
            new ReservationView(
                id: $reservation->id()->value(),
                tableId: $reservation->tableId()->value(),
                restaurantId: $reservation->restaurantId()->value(),
                status: $reservation->status()->value(),
                name: $reservation->name()->value(),
                startDate: $reservation->startDate()->value()->format('Y-m-d H:i:s'),
                endDate: $reservation->endDate()->value()->format('Y-m-d H:i:s'),
            ),
            $result[0]
        );
    }
}
