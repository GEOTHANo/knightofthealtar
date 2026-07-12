<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Present = 'Present';
    case Late = 'Late';
    case Absent = 'Absent';
    case Excused = 'Excused';

    /**
     * Get the human-readable label for the enum case.
     */
    public function label(): string
    {
        return $this->value;
    }

    /**
     * Get all backed values for the enum.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $case): string => $case->value,
            self::cases(),
        );
    }
}