<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'title', 'description', 'course', 'due_date', 'due_time', 'status', 'grade', 'max_grade', 'priority', 'notes'])]
class Assignment extends Model
{
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'due_time' => 'datetime:H:i',
            'grade' => 'decimal:2',
            'max_grade' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
