<?php

declare(strict_types=1);

namespace App\Tests\Unit\Reservations\Reservation\Application\Service;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Application\Service\AvailabilitySlotsAssembler;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use PHPUnit\Framework\TestCase;

class AvailabilitySlotsAssemblerTest extends TestCase
{
    public function test_it_should_return_an_array_with_one_element_set_to_available_if_no_reservations(): void
    {
        $availabilitySlotsAssembler = new AvailabilitySlotsAssembler();
        $result = $availabilitySlotsAssembler->invoke([]);

        $this->assertCount(1, $result);
        $this->assertTrue($result[0]->available);
    }

    public function test_it_should_return_slots_for_every_table(): void
    {
        $availabilitySlotsAssembler = new AvailabilitySlotsAssembler();

        $reservation1 = $this->createMock(Reservation::class);
        $reservation1->method('startDate')->willReturn(new DateTimeImmutable('10:00:00'));
        $reservation1->method('endDate')->willReturn(new DateTimeImmutable('11:00:00'));

        $reservation2 = $this->createMock(Reservation::class);
        $reservation2->method('startDate')->willReturn(new DateTimeImmutable('12:00:00'));
        $reservation2->method('endDate')->willReturn(new DateTimeImmutable('13:00:00'));

        $result = $availabilitySlotsAssembler->invoke([$reservation1, $reservation2]);

        $this->assertCount(4, $result);

        $this->assertTrue($result[0]->available);
        $this->assertEquals('00:00:00', $result[0]->start);
        $this->assertEquals('10:00:00', $result[0]->end);

        $this->assertFalse($result[1]->available);
        $this->assertEquals('10:00:00', $result[1]->start);
        $this->assertEquals('11:00:00', $result[1]->end);

        $this->assertTrue($result[2]->available);
        $this->assertEquals('11:00:00', $result[2]->start);
        $this->assertEquals('12:00:00', $result[2]->end);

        $this->assertFalse($result[3]->available);
        $this->assertEquals('12:00:00', $result[3]->start);
        $this->assertEquals('13:00:00', $result[3]->end);
    }
}
