<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FocusSession;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FocusTimerController extends Controller
{
    public function index(Request $request)
    {
        $topics = Topic::where('created_by', $request->user()->id)
            ->orderBy('title')->get();

        $todayMinutes = FocusSession::where('user_id', $request->user()->id)
            ->whereDate('completed_at', Carbon::today())
            ->sum('duration_minutes');

        $thisWeekMinutes = FocusSession::where('user_id', $request->user()->id)
            ->where('completed_at', '>=', Carbon::now()->startOfWeek())
            ->sum('duration_minutes');

        $totalMinutes = FocusSession::where('user_id', $request->user()->id)
            ->sum('duration_minutes');

        $history = FocusSession::where('user_id', $request->user()->id)
            ->with('topic')
            ->latest('completed_at')
            ->paginate(20);

        return view('student.focus.index', compact(
            'topics', 'todayMinutes', 'thisWeekMinutes', 'totalMinutes', 'history'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:180'],
            'break_minutes' => ['required', 'integer', 'min:0', 'max:60'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->focusSessions()->create([
            'duration_minutes' => $data['duration_minutes'],
            'break_minutes' => $data['break_minutes'],
            'topic_id' => $data['topic_id'] ?? null,
            'notes' => $data['notes'] ?? null,
            'completed_at' => Carbon::now(),
        ]);

        return redirect()->route('focus.index')
            ->with('success', 'Focus session logged!');
    }

    public function history(Request $request)
    {
        $sessions = FocusSession::where('user_id', $request->user()->id)
            ->with('topic')
            ->latest('completed_at')
            ->paginate(30);

        return view('student.focus.history', compact('sessions'));
    }
}
