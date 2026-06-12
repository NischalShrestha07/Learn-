<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PlannedSession;
use App\Models\StudyGoal;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudyPlannerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();

        $activeGoals = $user->studyGoals()
            ->where('status', 'active')
            ->latest()->get();

        $todaySessions = $user->plannedSessions()
            ->whereDate('scheduled_at', $now->toDateString())
            ->with('topic')
            ->orderBy('scheduled_at')
            ->get();

        $upcomingSessions = $user->plannedSessions()
            ->where('scheduled_at', '>', $now)
            ->whereDate('scheduled_at', '!=', $now->toDateString())
            ->where('status', 'scheduled')
            ->with('topic')
            ->orderBy('scheduled_at')
            ->take(10)
            ->get();

        $pastSessions = $user->plannedSessions()
            ->where('scheduled_at', '<', $now)
            ->where('status', 'scheduled')
            ->with('topic')
            ->orderByDesc('scheduled_at')
            ->take(5)
            ->get();

        $thisWeekMinutes = $user->studyGoals()
            ->where('status', 'active')
            ->where('goal_type', 'weekly')
            ->sum('current_minutes');

        $weeklyGoal = StudyGoal::query()
            ->where('user_id', $user->id)
            ->where('goal_type', 'weekly')
            ->where('status', 'active')
            ->latest()->first();

        $topics = Topic::where('created_by', $user->id)
            ->orderBy('title')->get();

        return view('student.planner.index', compact(
            'activeGoals', 'todaySessions', 'upcomingSessions',
            'pastSessions', 'thisWeekMinutes', 'weeklyGoal', 'topics'
        ));
    }

    public function storeGoal(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'goal_type' => ['required', 'in:daily,weekly,monthly,custom'],
            'target_minutes' => ['required', 'integer', 'min:1', 'max:100000'],
            'end_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $request->user()->studyGoals()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'goal_type' => $data['goal_type'],
            'target_minutes' => $data['target_minutes'],
            'start_date' => Carbon::now()->toDateString(),
            'end_date' => $data['end_date'] ?? null,
        ]);

        return redirect()->route('planner.index')
            ->with('success', 'Goal created successfully.');
    }

    public function updateGoal(Request $request, StudyGoal $goal)
    {
        abort_if($goal->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'goal_type' => ['required', 'in:daily,weekly,monthly,custom'],
            'target_minutes' => ['required', 'integer', 'min:1', 'max:100000'],
            'end_date' => ['nullable', 'date'],
        ]);

        $goal->update($data);

        return redirect()->route('planner.index')
            ->with('success', 'Goal updated successfully.');
    }

    public function updateGoalProgress(Request $request, StudyGoal $goal)
    {
        abort_if($goal->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'current_minutes' => ['required', 'integer', 'min:0'],
        ]);

        $goal->update(['current_minutes' => $data['current_minutes']]);

        return redirect()->route('planner.index')
            ->with('success', 'Progress updated.');
    }

    public function completeGoal(StudyGoal $goal)
    {
        abort_if($goal->user_id !== auth()->id(), 403);
        $goal->update(['status' => 'completed']);

        return redirect()->route('planner.index')
            ->with('success', 'Goal completed!');
    }

    public function destroyGoal(StudyGoal $goal)
    {
        abort_if($goal->user_id !== auth()->id(), 403);
        $goal->delete();

        return redirect()->route('planner.index')
            ->with('success', 'Goal removed.');
    }

    public function storeSession(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'scheduled_at' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->plannedSessions()->create($data);

        return redirect()->route('planner.index')
            ->with('success', 'Study session planned.');
    }

    public function updateSession(Request $request, PlannedSession $session)
    {
        abort_if($session->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'scheduled_at' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:scheduled,completed,cancelled'],
        ]);

        $session->update($data);

        return redirect()->route('planner.index')
            ->with('success', 'Session updated.');
    }

    public function completeSession(PlannedSession $session)
    {
        abort_if($session->user_id !== auth()->id(), 403);
        $session->update(['status' => 'completed']);

        return redirect()->route('planner.index')
            ->with('success', 'Session completed.');
    }

    public function destroySession(PlannedSession $session)
    {
        abort_if($session->user_id !== auth()->id(), 403);
        $session->delete();

        return redirect()->route('planner.index')
            ->with('success', 'Session removed.');
    }
}
