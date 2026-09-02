<?php

namespace App\Models;

use App\Enums\RebookingRule;
use App\Models\Traits\BranchScoped;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentConfiguration extends Model
{
    use BranchScoped;

    protected $fillable = [
        'branch_id',
        'time_interval_id',
        'capacity_per_interval',
        'grace_period_minutes',
        'rebooking_rule',
        'cancellation_cutoff_id',
    ];

    protected $casts = [
        'capacity_per_interval' => 'integer',
        'grace_period_minutes' => 'integer',
        'rebooking_rule' => RebookingRule::class,
    ];

    public function timeInterval(): BelongsTo
    {
        return $this->belongsTo(TimeInterval::class);
    }

    public function cancellationCutoff(): BelongsTo
    {
        return $this->belongsTo(CancellationCutoff::class);
    }

    public static function defaults(): array
    {
        return [
            'time_interval_id' => TimeInterval::where('minutes', 30)->value('id')
                ?? TimeInterval::active()->orderBy('minutes')->value('id'),
            'capacity_per_interval' => 5,
            'grace_period_minutes' => 15,
            'rebooking_rule' => RebookingRule::NextBusinessDay,
            'cancellation_cutoff_id' => CancellationCutoff::where('hours', 24)->value('id')
                ?? CancellationCutoff::active()->orderBy('hours')->value('id'),
        ];
    }

    public function slotDuration(): CarbonInterval
    {
        return CarbonInterval::minutes($this->timeInterval->minutes);
    }

    public function cancellationDeadlineFor(CarbonInterface $appointmentTime): Carbon
    {
        return Carbon::parse($appointmentTime)->subHours($this->cancellationCutoff->hours);
    }

    public function noShowDeadlineFor(CarbonInterface $appointmentTime): Carbon
    {
        return Carbon::parse($appointmentTime)->addMinutes($this->grace_period_minutes);
    }
}
