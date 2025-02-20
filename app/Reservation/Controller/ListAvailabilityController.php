<?php

declare(strict_types=1);

namespace App\Reservation\Controller;

use LastReservation\Reservations\Reservation\Application\Query\SearchAvailability;
use LastReservation\Reservations\Shared\Infrastructure\AvailableSlots15MinutesSplitter;
use LastReservation\Shared\Domain\Bus\QueryBus;
use LastReservation\Shared\Domain\RestaurantId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListAvailabilityController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly AvailableSlots15MinutesSplitter $availableSlots15MinutesSplitter,
    ) {
    }

    #[Route(
        path: '/reservations/availability',
        name: 'reservation.list_availability',
        methods: ['GET']
    )]
    public function __invoke(Request $request, RestaurantId $restaurantId): JsonResponse
    {
        $result = $this->queryBus->ask(
            new SearchAvailability(
                restaurantId: $restaurantId->value(),
                partySize: $request->query->getInt('party_size'),
                date: $request->query->get('date'),
            ),
        );

        // The query returns a view containing the available and busy slots for each table.
        // The Frontend should be in charge of display this information in slots of 15 min. But I do that
        //  as an Infrastructure service since I consider it is out of the scope of the API.
        $resultWith15minSlots = $this->availableSlots15MinutesSplitter->invoke($result);

        return new JsonResponse($resultWith15minSlots);
    }
}
