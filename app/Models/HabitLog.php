<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['habit_id', 'user_id', 'log_date', 'completed', 'value', 'note'])]
class HabitLog extends Model
{
    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'completed' => 'boolean',
            'value' => 'decimal:2',
        ];
    }

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
