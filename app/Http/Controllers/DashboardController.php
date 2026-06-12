<?php

namespace App\Http\Controllers;

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

        $progressCounts = TopicProgress::where('user_id', $user->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $bookmarkedTopics = $user->bookmarks()->with('topic')->latest()->take(8)->get();

        return view('dashboard', compact(
            'recentSessions',
            'weeklySeconds',
            'progressCounts',
            'bookmarkedTopics',
        ));
    }
}
