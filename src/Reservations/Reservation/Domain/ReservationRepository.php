<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

use LastReservation\Reservations\Shared\TableId;

interface ReservationRepository
{
    public function save(Reservation $reservation): void;
    
    public function findById(ReservationId $id): ?Reservation;
    
    /** @return list<Reservation> */
    public function findAll(): array;

    public function existsReservationForTheTableAndTime(
        TableId $tableId,
        ReservationStartDate $starts,
        ReservationEndDate $ends,
    ): bool;
} 