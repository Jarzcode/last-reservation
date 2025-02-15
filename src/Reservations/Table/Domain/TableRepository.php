<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

interface TableRepository
{
    public function save(Table $table): void;
    
    public function findById(TableId $id): ?Table;
    
    /** @return list<Table> */
    public function findAll(?TableCapacity $capacity = null): array;
}
