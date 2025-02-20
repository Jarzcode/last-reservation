<?php

declare(strict_types=1);

namespace App\Reservation\Controller;

use LastReservation\Reservations\Reservation\Application\Query\SearchReservations;
use LastReservation\Shared\Domain\Bus\QueryBus;
use LastReservation\Shared\Domain\RestaurantId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListReservationsController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {
    }

    #[Route(
        path: '/reservations',
        name: 'reservation.list',
        methods: ['GET']
    )]
    public function __invoke(RestaurantId $restaurantId): JsonResponse
    {
        $result = $this->queryBus->ask(
            new SearchReservations(restaurantId: $restaurantId->value()
            )
        );

        return new JsonResponse($result);
    }
}
