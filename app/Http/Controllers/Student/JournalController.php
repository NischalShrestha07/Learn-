<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->journalEntries()->latest('date');

        if ($request->filled('month')) {
            $month = Carbon::parse($request->month);
            $query->whereYear('date', $month->year)
                ->whereMonth('date', $month->month);
        }

        $entries = $query->paginate(20);
        $streak = $this->calculateStreak($request->user());

        $prompts = [
            'What did I learn today?',
            'What was the most challenging concept?',
            'How can I apply what I learned?',
            'What questions do I still have?',
            'What study method worked best today?',
            'What should I focus on tomorrow?',
            'What progress did I make on my goals?',
            'What distracted me and how can I avoid it?',
        ];

        return view('student.journal.index', compact('entries', 'streak', 'prompts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => ['required', 'string'],
            'date' => ['required', 'date'],
            'mood' => ['nullable', 'integer', 'min:1', 'max:5'],
            'prompt' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->journalEntries()->create($data);

        return redirect()->route('journal.index')
            ->with('success', 'Journal entry saved.');
    }

    public function update(Request $request, JournalEntry $entry)
    {
        abort_if($entry->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'content' => ['required', 'string'],
            'date' => ['required', 'date'],
            'mood' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $entry->update($data);

        return redirect()->route('journal.index')
            ->with('success', 'Entry updated successfully.');
    }

    public function destroy(JournalEntry $entry)
    {
        abort_if($entry->user_id !== auth()->id(), 403);
        $entry->delete();

        return redirect()->route('journal.index')
            ->with('success', 'Entry deleted.');
    }

    private function calculateStreak($user): int
    {
        $streak = 0;
        $date = Carbon::today();

        while ($user->journalEntries()->whereDate('date', $date)->exists()) {
            $streak++;
            $date->subDay();
        }

        $latest = $user->journalEntries()->latest('date')->first();
        if (!$latest || !$latest->date->between(Carbon::today()->subDay(), Carbon::today())) {
            return 0;
        }

        return $streak;
    }
}
