<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\UseCase\Send;

use LastReservation\Reservations\Notification\Domain\NotificationRepository;
use Psr\Log\LoggerInterface;

final class SendEmailNotificationHandler
{
    public function __construct(
        private readonly NotificationRepository $repository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SendEmailNotification $command): void
    {
        $notifications = $this->repository->findByDate(
            $command->restaurantId,
            $command->when
        );

        foreach ($notifications as $notification) {
            $this->logger->info('Sending email notification', [
                'restaurant_id' => $notification->restaurantId()->value(),
                'email' => $notification->email()->value(),
                'message' => $notification->message()->value(),
                'when' => $notification->when()->value()->format('Y-m-d H:i'),
            ]);
        }
    }
}
