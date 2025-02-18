<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\RestaurantId;

interface ReservationRepository
{
    public function save(Reservation $reservation): void;
    
    public function findById(ReservationId $id, RestaurantId $restaurantId): ?Reservation;
    
    /** @return list<Reservation> */
    public function findAll(RestaurantId $restaurantId): array;

    public function existsReservationForTheTableAndTime(
        TableId $tableId,
        ReservationStartDate $starts,
        ReservationEndDate $ends,
    ): bool;
} 