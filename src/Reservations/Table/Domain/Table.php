<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

use LastReservation\User\Domain\AggregateRoot;

class Table extends AggregateRoot
{
    private function __construct(
        private readonly TableId $id,
        private readonly TableName $name,
        private readonly TableCapacity $capacity,
        private readonly TableLocation $location,
        private readonly TableDescription $description,
    ){
    }

    public static function create(
        TableId $id,
        TableName $name,
        TableCapacity $capacity,
        TableLocation $location,
        TableDescription $description,
    ): self {
        $table = new self(
            $id,
            $name,
            $capacity,
            $location,
            $description,
        );

        $table->record(new TableCreated(
                $this->id->value(), 
                $this->name->value(), 
                $this->capacity->value(), 
                $this->location->value(), 
                $this->description->value(),
            )
        );

        return $table;
    }
}
