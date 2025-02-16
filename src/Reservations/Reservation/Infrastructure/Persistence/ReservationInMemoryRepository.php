<?php 

namespace LastReservation\Reservations\Reservation\Infrastructure\Persistence;

use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\RestaurantId;

final class ReservationInMemoryRepository implements ReservationRepository
{
    /** @var array<string, Reservation> */
    private array $reservations = [];

    public function save(Reservation $reservation): void
    {
        $this->reservations[$reservation->id()->value()] = $reservation;
    }

    public function findById(ReservationId $id, RestaurantId $restaurantId): ?Reservation
    {
        $reservation = $this->reservations[$id->value()];

        if ($reservation === null) {
            return null;
        }

        return $reservation->restaurantId()->equals($restaurantId) ? $reservation : null;
    }

    public function findAll(): array
    {
        return array_values($this->reservations);
    }

    public function existsReservationForTheTableAndTime(
        TableId $tableId,
        ReservationStartDate $starts,
        ReservationEndDate $ends,
    ): bool {
        $matchedReservations = array_filter(
            $this->reservations,
            function (Reservation $reservation) use ($tableId, $starts, $ends) {
                return
                    $reservation->tableId()->equals($tableId) &&
                    ($reservation->endDate() >= $starts && $reservation->endDate() <= $ends);
            });

        return count($matchedReservations) > 0;
    }
}
