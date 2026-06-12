<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'title', 'course', 'exam_date', 'start_time', 'duration_minutes', 'location', 'exam_type', 'status', 'grade', 'max_grade', 'notes'])]
class Exam extends Model
{
    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'start_time' => 'datetime:H:i',
            'duration_minutes' => 'integer',
            'grade' => 'decimal:2',
            'max_grade' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
