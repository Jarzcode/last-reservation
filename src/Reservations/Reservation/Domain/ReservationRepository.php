<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Domain;

interface ReservationRepository
{
    public function save(Reservation $reservation): void;
    
    public function findById(ReservationId $id): ?Reservation;
    
    public function findAll(): array;
} 