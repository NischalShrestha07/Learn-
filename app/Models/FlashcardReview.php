<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['flashcard_id', 'user_id', 'quality', 'reviewed_at', 'next_review_at'])]
class FlashcardReview extends Model
{
    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'next_review_at' => 'datetime',
        ];
    }

    public function flashcard(): BelongsTo
    {
        return $this->belongsTo(Flashcard::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
