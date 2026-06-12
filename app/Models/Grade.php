<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'course', 'credits', 'score', 'letter_grade', 'grade_points', 'semester', 'year', 'is_elective', 'notes'])]
class Grade extends Model
{
    protected function casts(): array
    {
        return [
            'credits' => 'decimal:1',
            'score' => 'decimal:2',
            'grade_points' => 'decimal:2',
            'year' => 'integer',
            'is_elective' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
