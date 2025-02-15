<?php 

namespace LastReservation\Reservations\Table\Infrastructure\Persistence;

use LastReservation\Reservations\Shared\Table;
use LastReservation\Reservations\Shared\TableCapacity;
use LastReservation\Reservations\Shared\TableId;
use LastReservation\Reservations\Shared\TableLocation;
use LastReservation\Reservations\Shared\TableName;
use LastReservation\Reservations\Shared\TableRepository;
use LastReservation\Shared\Domain\RestaurantId;

final class TableInMemoryRepository implements TableRepository
{
    /** @var array<string, Table> */
    private array $tables;

    public function __construct()
    {
        $this->tables = [
            '1' => new Table(
                id: TableId::create('01JM0Q5229XEV9XDQR2ZXVFM6R'),
                restaurantId: RestaurantId::create('123'),
                name: TableName::create('Table 1'),
                capacity: TableCapacity::create(4),
                location: TableLocation::create('Location 1'),
                description: null,
            ),
            '2' => new Table(
                id: TableId::create('01JM0Q6EYP5QR9B2RGV7V3MSVT'),
                restaurantId: RestaurantId::create('123'),
                name: TableName::create('Table 2'),
                capacity: TableCapacity::create(4),
                location: TableLocation::create('Location 2'),
                description: null,
            ),
            '3' => new Table(
                id: TableId::create('01JM0Q6SJS0TYA917D6EX10EF9'),
                restaurantId: RestaurantId::create('123'),
                name: TableName::create('Table 3'),
                capacity: TableCapacity::create(2),
                location: TableLocation::create('Location 3'),
                description: null,
            ),
            '4' => new Table(
                id: TableId::create('01JM0Q73N7SV8C57WAWR6N101X'),
                restaurantId: RestaurantId::create('123'),
                name: TableName::create('Table 4'),
                capacity: TableCapacity::create(8),
                location: TableLocation::create('Location 4'),
                description: null,
            ),
            '5' => new Table(
                id: TableId::create('01JM0Q7EZDSTWXQEVG3YN8JN2P'),
                restaurantId: RestaurantId::create('123'),
                name: TableName::create('Table 5'),
                capacity: TableCapacity::create(12),
                location: TableLocation::create('Location 5'),
                description: null,
            ),
            '6' => new Table(
                id: TableId::create('01JM0Q7QHJ301E1VV4HP9SD77K'),
                restaurantId: RestaurantId::create('123'),
                name: TableName::create('Table 6'),
                capacity: TableCapacity::create(4),
                location: TableLocation::create('Location 1'),
                description: null,
            ),
        ];
    }

    public function save(Table $table): void
    {
        $this->tables[$table->id()->value()] = $table;
    }

    public function findById(TableId $id): ?Table
    {
        return $this->tables[$id->value()] ?? null;
    }

    //TODO: Implement the Criteria pattern
    public function findAll(?TableCapacity $capacity = null): array
    {
        if ($capacity === null) {
            return array_values($this->tables);
        }

        $tables = [];
        foreach($this->tables as $table){
            if ($table->capacity() >= $capacity) {
                $tables[] = $table;
            }
        }

        return $tables;
    }
}
