<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'title', 'description', 'goal_type', 'target_minutes', 'current_minutes', 'start_date', 'end_date', 'status'])]
class StudyGoal extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function progressPercent(): int
    {
        if ($this->target_minutes === 0) return 0;
        return min(100, (int) round(($this->current_minutes / $this->target_minutes) * 100));
    }
}
