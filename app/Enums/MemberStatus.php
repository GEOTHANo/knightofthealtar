<?php

namespace App\Enums;

enum MemberStatus: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
    case Alumni = 'Alumni';

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