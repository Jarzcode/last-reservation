<?php

declare(strict_types=1);

namespace App\Reservation\Controller;

use Exception;
use LastReservation\Reservations\Reservation\Application\UseCase\Update\UpdateReservation;
use LastReservation\Reservations\Reservation\Domain\ReservationEndDate;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Reservations\Reservation\Domain\ReservationName;
use LastReservation\Reservations\Reservation\Domain\ReservationPartySize;
use LastReservation\Reservations\Reservation\Domain\ReservationStartDate;
use LastReservation\Shared\Domain\Bus\CommandBus;
use LastReservation\Shared\Domain\RestaurantId;
use LastReservation\Shared\Domain\UlidIdentification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UpdateReservationController
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    #[Route(
        path: '/reservations/{id}',
        name: 'reservation.update',
        requirements: [
            'id' => UlidIdentification::EMBEDDED_PATTERN,
        ],
        methods: ['PUT']
    )]
    public function __invoke(Request $request, RestaurantId $restaurantId): JsonResponse
    {
        //TODO: Check authorization

        try {
            $reservationId = ReservationId::create($request->get('id'));
            $restaurantId = RestaurantId::create($request->get('restaurantId'));
            $partySize = ReservationPartySize::create($request->get('party_size'));
            $name = ReservationName::create($request->get('name'));
            $start = ReservationStartDate::create($request->get('start'));
            $end = ReservationEndDate::create($request->get('end'), $start);

            $this->commandBus->handle(
                new UpdateReservation(
                    id: $reservationId,
                    restaurantId: $restaurantId,
                    name: $name,
                    partySize: $partySize,
                    start: $start,
                    end: $end,
                ),
            );

            return new JsonResponse(['id' => $reservationId], Response::HTTP_OK);

        } catch (Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
