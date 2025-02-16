<?php

declare(strict_types=1);

namespace App\User\Controller;

use Exception;
use LastReservation\Reservations\Reservation\Application\UseCase\Create\CreateReservation;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Shared\Domain\Bus\CommandBus;
use LastReservation\Shared\Domain\Log\Logger;
use LastReservation\Shared\Domain\RestaurantId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateReservationController
{
    public function __construct(
        private CommandBus $commandBus, // This is the domain interface, and the implementation could be the Symfony Bus
        private Logger $logger, // This is the domain interface, the implementation could be Monolog
    ) {
    }

    #[Route(
        path: '/reservations/',
        name: 'reservation.create',
        methods: ['POST']
    )]
    public function __invoke(Request $request, RestaurantId $restaurantId): JsonResponse
    {
        $reservationId = ReservationId::generate();
        $this->logger->info('reservation_creation_process_start', ['id' => $reservationId]);

        try {
            $this->commandBus->handle(
                new CreateReservation(
                    restaurantId: $restaurantId,
                    id: $reservationId,
                    name: $request->get('name'),
                    email: $request->get('email'),
                    phone: $request->get('phone'),
                    when: $request->get('when'),
                    partySize: $request->get('party_size'),
                )
            );

            $this->logger->info('reservation_creation_process_finished', ['id' => $reservationId]);

            return new JsonResponse(['id' => $reservationId], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            $this->logger->error('reservation_creation_process_failed',
                [
                    'id' => $reservationId,
                    'message' => $exception->getMessage()
                ]
            );
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}