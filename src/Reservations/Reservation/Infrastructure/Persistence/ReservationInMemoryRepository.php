<?php 

namespace LastReservation\Reservations\Reservation\Infrastructure\Persistence;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Domain\Reservation;
use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationRepository;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Reservations\Reservation\Domain\ReservationStatus;
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

    public function findAll(RestaurantId $restaurantId): array
    {
        return array_filter(
            $this->reservations,
            function (Reservation $reservation) use ($restaurantId) {
                return $reservation->restaurantId()->equals($restaurantId);
            });
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

    public function findReservationsByTableAndDate(
        TableId $tableId,
        RestaurantId $restaurantId,
        DateTimeImmutable $date,
    ): array {
        $reservations = array_filter(
            $this->reservations,
            function (Reservation $reservation) use ($tableId, $restaurantId, $date) {
                return
                    $reservation->tableId()->equals($tableId) &&
                    $reservation->restaurantId()->equals($restaurantId) &&
                    $reservation->startDate()->value()->format('Y-m-d') === $date->format('Y-m-d');
            });

        $this->orderByStartDateAsc($reservations);

        return $reservations;
    }

    public function findFirstWhiteListedByPartySize(
        RestaurantId $restaurantId,
        ReservationPartySize $partySize,
        ReservationStartDate $startDate,
    ): ?Reservation {
        $reservations = array_filter(
            $this->reservations,
            function (Reservation $reservation) use ($restaurantId, $partySize, $startDate) {
                return
                    $reservation->restaurantId()->equals($restaurantId) &&
                    $reservation->partySize()->equals($partySize) &&
                    $this->areInTheSameDay($reservation, $startDate) &&
                    $reservation->status()->isWhiteListed();
            });

        return reset($reservations) ?: null;
    }

    private function areInTheSameDay(Reservation $reservation, ReservationStartDate $startDate): bool
    {
        return
            (new DateTimeImmutable($reservation->startDate()->value()))
                ->format('Y-m-d') ===
            (new DateTimeImmutable($startDate->value()))
                ->format('Y-m-d');
    }

    /** @param list<Reservation> $reservations */
    private function orderByStartDateAsc(array &$reservations): void
    {
        usort($reservations, function (Reservation $a, Reservation $b) {
            return $a->startDate() <=> $b->startDate();
        });
    }
}
