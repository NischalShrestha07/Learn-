<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'created_by');
    }

    public function learningSessions(): HasMany
    {
        return $this->hasMany(LearningSession::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(TopicProgress::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function sectionViews(): HasMany
    {
        return $this->hasMany(SectionView::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function noteTags(): HasMany
    {
        return $this->hasMany(NoteTag::class);
    }

    public function studyGoals(): HasMany
    {
        return $this->hasMany(StudyGoal::class);
    }

    public function plannedSessions(): HasMany
    {
        return $this->hasMany(PlannedSession::class);
    }

    public function flashcardDecks(): HasMany
    {
        return $this->hasMany(FlashcardDeck::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function focusSessions(): HasMany
    {
        return $this->hasMany(FocusSession::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function habits(): HasMany
    {
        return $this->hasMany(Habit::class);
    }

    public function habitLogs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }
}
