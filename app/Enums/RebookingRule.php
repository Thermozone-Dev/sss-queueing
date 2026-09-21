<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RebookingRule: string implements HasLabel
{
    case SameDay = 'same_day';
    case NextBusinessDay = 'next_business_day';

    public function getLabel(): string
    {
        return match ($this) {
            self::SameDay => 'Same Day',
            self::NextBusinessDay => 'Next Business Day',
        };
    }
}
