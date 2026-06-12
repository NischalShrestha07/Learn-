<?php

namespace App\Http\Controllers;

use App\Models\FocusSession;
use App\Models\LearningSession;
use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $recentSessions = LearningSession::with('topic')
            ->where('user_id', $user->id)
            ->whereNotNull('ended_at')
            ->latest('started_at')
            ->take(5)
            ->get();

        $weeklySeconds = LearningSession::where('user_id', $user->id)
            ->where('started_at', '>=', Carbon::now()->startOfWeek())
            ->whereNotNull('duration_seconds')
            ->sum('duration_seconds');

        $topicCount = $user->topics()->count();
        $noteCount = $user->notes()->count();
        $deckCount = $user->flashcardDecks()->count();

        $recentNotes = $user->notes()->with('tags')->latest()->take(4)->get();
        $recentTopics = $user->topics()->withCount('sections')->latest()->take(4)->get();

        $todayFocusMinutes = FocusSession::where('user_id', $user->id)
            ->whereDate('completed_at', Carbon::today())
            ->sum('duration_minutes');

        $activeGoals = $user->studyGoals()->where('status', 'active')->count();
        $todaySessions = $user->plannedSessions()
            ->whereDate('scheduled_at', Carbon::today())
            ->where('status', 'scheduled')
            ->count();

        $progressCounts = TopicProgress::where('user_id', $user->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $bookmarkedTopics = $user->bookmarks()->with('topic')->latest()->take(5)->get();

        return view('dashboard', compact(
            'recentSessions', 'weeklySeconds', 'topicCount', 'noteCount', 'deckCount',
            'recentNotes', 'recentTopics', 'todayFocusMinutes', 'activeGoals',
            'todaySessions', 'progressCounts', 'bookmarkedTopics',
        ));
    }
}
