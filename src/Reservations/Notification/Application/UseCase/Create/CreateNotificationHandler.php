<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\UseCase\Create;

use LastReservation\Reservations\Notification\Domain\EmailNotification;
use LastReservation\Reservations\Notification\Domain\NotificationRepository;

final class CreateNotificationHandler
{
    public function __construct(private readonly NotificationRepository $repository)
    {
    }

    public function __invoke(CreateNotification $command): void
    {
        $notification = EmailNotification::create(
            $command->id,
            $command->email,
            $command->message,
            $command->when
        );

        $this->repository->save($notification);
    }
}
