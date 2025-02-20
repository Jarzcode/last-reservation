<?php

declare(strict_types=1);

namespace LastReservation\Shared\Infrastructure\ConsoleCommand;

use DateTimeImmutable;
use LastReservation\Reservations\Notification\Application\Query\NotificationView;
use LastReservation\Reservations\Notification\Application\Query\SearchNotificationsByDate;
use LastReservation\Reservations\Notification\Application\UseCase\Send\SendEmailNotification;
use LastReservation\Reservations\Notification\Domain\NotificationEmail;
use LastReservation\Reservations\Notification\Domain\NotificationMessage;
use LastReservation\Reservations\Notification\Domain\NotificationWhen;
use LastReservation\Shared\Domain\Bus\CommandBus;
use LastReservation\Shared\Domain\Bus\QueryBus;
use LastReservation\Shared\Domain\RestaurantId;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is the console command to send email notifications to the owners of the reservations.
 * The idea is that this command will be executed by a cron job, that runs this command every minute.
 * If the reservation starts in one hour, the owner will receive an email notification.
 */
final class SendEmailNotificationCommand extends Command
{
    private const PREVIOUS_TIME = '1 hour';
    protected static $defaultName = 'last-app:send-email-notification';

    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send email notifications for reservations')
            ->addArgument('restaurantId', InputArgument::REQUIRED, 'The ID of the restaurant');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $restaurantId =  RestaurantId::create($input->getArgument('restaurantId'));

        /** @var list<NotificationView> $notificationsToSend */
        $notificationsToSend = $this->queryBus->ask(
            new SearchNotificationsByDate(
                $restaurantId->value(),
                (new DateTimeImmutable('now +' . self::PREVIOUS_TIME))->format('Y-m-d H:i:s'))
        );

        foreach ($notificationsToSend as $notification) {
            $this->commandBus->handle(
                new SendEmailNotification(
                    restaurantId: $restaurantId,
                    email: NotificationEmail::create($notification->email),
                    message: NotificationMessage::create($notification->message),
                    when: NotificationWhen::create(new DateTimeImmutable($notification->when))
                )
            );
        }

        $output->writeln('Email notification sent successfully.');

        return Command::SUCCESS;
    }
}
