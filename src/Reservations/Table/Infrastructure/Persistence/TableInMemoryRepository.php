<?php 

namespace LastReservation\Reservations\Table\Infrastructure\Persistence;

use LastReservation\Reservations\Table\Domain\Table;
use LastReservation\Reservations\Table\Domain\TableId;
use LastReservation\Reservations\Table\Domain\TableRepository;

final class TableInMemoryRepository implements TableRepository
{
    /** @var array<string, Table> */
    private array $tables = [];

    public function save(Table $table): void
    {
        $this->tables[$table->id()->value()] = $table;
    }

    public function findById(TableId $id): ?Table
    {
        return $this->tables[$id->value()] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->tables);
    }
}
