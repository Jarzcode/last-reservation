<?php

declare(strict_types=1);

namespace App\Reservation\Controller;

use Exception;
use LastReservation\Reservations\Reservation\Application\UseCase\Cancel\CancelReservation;
use LastReservation\Reservations\Reservation\Domain\ReservationId;
use LastReservation\Shared\Domain\Bus\CommandBus;
use LastReservation\Shared\Domain\Log\Logger;
use LastReservation\Shared\Domain\RestaurantId;
use LastReservation\Shared\Domain\UlidIdentification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CancelReservationController
{
    public function __construct(
        private readonly CommandBus $commandBus, // This is the domain interface, and the implementation could be the Symfony Bus
        private readonly Logger $logger // This is the domain interface, the implementation could be Monolog
    ) {
    }

    #[Route(
        path: '/reservations/{id}/cancel',
        name: 'reservation.cancel',
        requirements: [
            'id' => UlidIdentification::EMBEDDED_PATTERN,
        ],
        methods: ['PATCH']
    )]
    public function __invoke(string $id, RestaurantId $restaurantId): JsonResponse
    {
        //TODO: Check authorization

        $this->logger->info('reservation_cancellation_process_start', ['id' => $id]);

        try {
            $this->commandBus->handle(
                new CancelReservation(
                    reservationId: ReservationId::create($id),
                    restaurantId: $restaurantId,
                )
            );

            $this->logger->info('reservation_cancellation_process_finished', ['id' => $id]);

            return new JsonResponse(['id' => $id], Response::HTTP_OK);
        } catch (Exception $exception) {
            $this->logger->error('reservation_cancellation_process_failed',
                [
                    'id' => $id,
                    'message' => $exception->getMessage()
                ]
            );
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
