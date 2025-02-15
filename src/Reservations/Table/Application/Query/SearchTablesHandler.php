<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Application\Query;

use LastReservation\Reservations\Table\Domain\TableCapacity;
use LastReservation\Reservations\Table\Domain\TableRepository;

final class SearchTablesHandler
{
    public function __construct(
        private readonly TableRepository $repository,
        private readonly TableViewAssembler $assembler,
    ) {
    }

    /** @return list<TableView> */
    public function __invoke(SearchTables $query): array
    {
        $tables = $this->repository->findAll(TableCapacity::create($query->capacity));

        return $this->assembler->invoke($tables);
    }
}