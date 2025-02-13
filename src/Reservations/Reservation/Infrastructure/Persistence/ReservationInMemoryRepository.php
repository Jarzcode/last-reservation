<?php 

namespace LastReservation\Reservations\Reservation\Infrastructure\Persistence;



use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;

final class ReservationInMemoryRepository implements ReservationRepository
{
    /** @var array<string, Reservation> */
    private array $reservations = [];

    public function save(Reservation $reservation): void
    {
        $this->reservations[$reservation->id()->value()] = $reservation;
    }

    public function findById(ReservationId $id): ?Reservation
    {
        return $this->reservations[$id->value()] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->reservations);
    }
}
