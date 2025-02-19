<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Table\Domain;

use LastReservation\Reservations\Shared\TableId;
use LastReservation\Shared\Domain\AggregateRoot;
use LastReservation\Shared\Domain\RestaurantId;

class Table extends AggregateRoot
{
    public function __construct(
        private readonly TableId $id,
        private readonly RestaurantId $restaurantId,
        private readonly TableName $name,
        private readonly TableCapacity $capacity,
        private readonly TableLocation $location,
        private readonly ?TableDescription $description,
    ){
    }

    public static function create(
        TableId $id,
        RestaurantId $restaurantId,
        TableName $name,
        TableCapacity $capacity,
        TableLocation $location,
        TableDescription $description,
    ): self {
        $table = new self(
            id: $id,
            restaurantId: $restaurantId,
            name: $name,
            capacity: $capacity,
            location: $location,
            description: $description,
        );

        $table->record(new TableCreated(
                $id->value(), 
                $name->value(), 
                $description->value(), 
                $capacity->value(), 
                $location->value(),
            )
        );

        return $table;
    }

    public function id(): TableId
    {
        return $this->id;
    }

    public function name(): TableName
    {
        return $this->name;
    }

    public function capacity(): TableCapacity
    {   
        return $this->capacity;
    }

    public function location(): TableLocation
    {
        return $this->location;
    }

    public function restaurantId()
    {
        return $this->restaurantId;
    }
}
