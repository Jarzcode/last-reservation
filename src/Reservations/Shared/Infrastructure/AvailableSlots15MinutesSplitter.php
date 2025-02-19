<?php

declare(strict_types=1);

namespace LastReservation\Reservations\Shared\Infrastructure;

use LastReservation\Reservations\Reservation\Application\Query\AvailabilitySlotView;
use LastReservation\Reservations\Reservation\Application\Query\TableAvailabilityView;

final class AvailableSlots15MinutesSplitter
{
    /** @return list<TableAvailabilityView> */
    public function invoke(array $fullTablesAvailability): array
    {
        $resultWith15minSlots = [];
        foreach ($fullTablesAvailability as $tableAvailabilityView) {
            $availabilitySlots = [];
            foreach ($tableAvailabilityView->availabilitySlots as $availabilitySlot) {
                $availabilitySlots = array_merge($availabilitySlots, $this->splitSlotInto15Min($availabilitySlot));
            }
            $resultWith15minSlots[] = new TableAvailabilityView(
                table: $tableAvailabilityView->table,
                availabilitySlots: $availabilitySlots,
            );
        }

        return $resultWith15minSlots;
    }

    /** @return list<AvailabilitySlotView> */
    private function splitSlotInto15Min(AvailabilitySlotView $availabilitySlot): array
    {
        // Do not split a slot if it is not available
        if ($availabilitySlot->available === false) {
            return [$availabilitySlot];
        }

        $slots = [];
        $start = $availabilitySlot->start;
        $end = $availabilitySlot->end;
        while ($start < $end) {
            $slots[] = new AvailabilitySlotView(
                start: $start,
                end: $start->modify('+15 minutes'),
                available: true,
            );
            $start = $start->modify('+15 minutes');
        }

        return $slots;
    }
}
