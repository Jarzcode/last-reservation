<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Reservation\Application\Service;

use DateTimeImmutable;
use LastReservation\Reservations\Reservation\Application\Query\AvailabilitySlotView;
use LastReservation\Reservations\Reservation\Domain\Reservation;

class AvailabilitySlotsAssembler
{
    /**
     * @param list<Reservation> $tableReservationsForTheDate
     * @return list<AvailabilitySlotView>
     */
    public function invoke(array $tableReservationsForTheDate): array
    {
        $startTime = new DateTimeImmutable(Reservation::START_TIME); //TODO: Take it from the restaurant settings
        $endTime = new DateTimeImmutable(Reservation::END_TIME); //TODO: Take it from the restaurant settings
        $tableSlots = [];

        if (empty($tableReservationsForTheDate)) {
            return [
                new AvailabilitySlotView(
                    start: $startTime->format('H:i:s'),
                    end: $endTime->format('H:i:s'),
                    available: true,
                )
            ];
        }

        for ($i = 0; $i <= count($tableReservationsForTheDate); $i++) {
            $previousReservation = $tableReservationsForTheDate[$i - 1] ?? null;
            $currentReservation = $tableReservationsForTheDate[$i];

            $initialSlot = $this->setInitialSlot(
                previousReservation: $previousReservation,
                currentReservation: $currentReservation,
                startTime: $startTime
            );

            if ($initialSlot) {
                $tableSlots[] = $initialSlot;
            }

            $tableSlots = $this->setSlotsBetweenReservations($previousReservation, $currentReservation, $tableSlots);

            $finalSlot = $this->setFinalSlot(
                currentReservation: $currentReservation,
                endTime: $endTime,
                numberOfReservations: count($tableReservationsForTheDate),
                reservationIndex: $i,
            );

            if ($finalSlot) {
                $tableSlots[] = $finalSlot;
            }
        }

        return $tableSlots;
    }

    private function setInitialSlot(
        ?Reservation $previousReservation,
        Reservation $currentReservation,
        DateTimeImmutable $startTime
    ): ?AvailabilitySlotView {
        if ($previousReservation === null) {
            return null;
        }

        if ($currentReservation->startDate()->value() > $startTime) {
            return  new AvailabilitySlotView(
                start: $startTime->format('H:i:s'),
                end: $currentReservation->startDate()->value()->format('H:i:s'),
                available: true,
            );
        }

        return null;
    }

    /**
     * @param Reservation|null $previousReservation
     * @param Reservation $currentReservation
     * @return list<AvailabilitySlotView>
     */
    public function setSlotsBetweenReservations(
        ?Reservation $previousReservation,
        Reservation $currentReservation,
    ): array {
        $tableSlots = [];

        if (
            $previousReservation !== null
            && $currentReservation->startDate()->value() > $previousReservation->endDate()->value()
        ) {
            $tableSlots[] = new AvailabilitySlotView(
                start: $previousReservation->endDate()->value()->format('H:i:s'),
                end: $currentReservation->startDate()->value()->format('H:i:s'),
                available: true,
            );
        }

        $tableSlots[] = new AvailabilitySlotView(
            start: $currentReservation->startDate()->value()->format('H:i:s'),
            end: $currentReservation->endDate()->value()->format('H:i:s'),
            available: false,
        );

        return $tableSlots;
    }

    private function setFinalSlot(
        Reservation $currentReservation,
        DateTimeImmutable $endTime,
        int $numberOfReservations,
        int $reservationIndex
    ): ?AvailabilitySlotView {
        if ($numberOfReservations > $reservationIndex) {
            return null;
        }

        if ($currentReservation->endDate()->value() < $endTime) {
            return new AvailabilitySlotView(
                start: $currentReservation->endDate()->value()->format('H:i:s'),
                end: $endTime->format('H:i:s'),
                available: true,
            );
        }

        return null;
    }
}
