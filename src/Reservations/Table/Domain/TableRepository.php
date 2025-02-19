<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\RestaurantId;

interface TableRepository
{
    public function save(Table $table): void;
    
    public function findById(TableId $id): ?Table;
    
    /** @return list<Table> */
    public function findAll(RestaurantId $restaurantId, ?TableCapacity $capacity = null): array;
}
