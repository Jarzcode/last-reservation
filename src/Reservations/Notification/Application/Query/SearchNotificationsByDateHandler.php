<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Notification\Application\Query;

use DateTimeImmutable;
use LastReservation\Reservations\Notification\Domain\NotificationRepository;
use LastReservation\Reservations\Notification\Domain\NotificationWhen;
use LastReservation\Shared\Domain\RestaurantId;

final class SearchNotificationsByDateHandler
{
    public function __construct(
        private readonly NotificationRepository $repository,
        private readonly NotificationViewAssembler $assembler,
    ) {
    }

    /** @return list<NotificationView> */
    public function __invoke(SearchNotificationsByDate $query): array
    {
        $notifications = $this->repository->findByDate(
            restaurantId: RestaurantId::create($query->restaurantId),
            when: NotificationWhen::create(new DateTimeImmutable($query->date))
        );

        return array_map(
            fn($notification) => $this->assembler->invoke($notification),
            $notifications
        );
    }
}
