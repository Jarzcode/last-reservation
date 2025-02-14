<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Application\Query;

use LastReservation\Reservations\Table\Domain\Table;

final class TableViewAssembler
{
    /** 
     * @param list<Table> $tables 
     * @return list<TableView>
     * */
    public function invoke(array $tables): array
    {
        return array_map(
            fn (Table $table): TableView => new TableView(
                id: $table->id()->value(),
                name: $table->name()->value(),
                capacity: $table->capacity()->value(),
                location: $table->location()->value(),
            ),
            $tables
        );
    }
} 