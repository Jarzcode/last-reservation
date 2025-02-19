<?php

declare(strict_types=1);

namespace App\User\Controller;

use LastReservation\Reservations\Reservation\Application\Query\SearchAvailability;
use LastReservation\Shared\Domain\Bus\QueryBus;
use LastReservation\Shared\Domain\RestaurantId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ListAvailabilityController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request, RestaurantId $restaurantId): JsonResponse
    {
        $result = $this->queryBus->ask(
            new SearchAvailability($restaurantId->value(), $request->query->getInt('party_size')),
        );

        return new JsonResponse($result);
    }
}
