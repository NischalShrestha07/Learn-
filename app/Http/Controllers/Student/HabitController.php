<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function index(Request $request)
    {
        $habits = $request->user()->habits()->where('is_active', true)->orderBy('name')->get();
        $today = now()->toDateString();

        foreach ($habits as $habit) {
            $habit->today_log = $habit->logs()->where('log_date', $today)->first();
            $habit->streak = $this->calculateStreak($habit);
        }

        return view('student.habits.index', compact('habits', 'today'));
    }

    public function create()
    {
        return view('student.habits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:7'],
            'frequency' => ['required', 'in:daily,weekly,weekdays'],
            'target_value' => ['nullable', 'numeric', 'min:0'],
            'target_unit' => ['nullable', 'string', 'max:50'],
        ]);

        $request->user()->habits()->create($data);

        return redirect()->route('habits.index')
            ->with('success', 'Habit created successfully.');
    }

    public function edit(Habit $habit)
    {
        abort_if($habit->user_id !== auth()->id(), 403);
        return view('student.habits.edit', compact('habit'));
    }

    public function update(Request $request, Habit $habit)
    {
        abort_if($habit->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:7'],
            'frequency' => ['required', 'in:daily,weekly,weekdays'],
            'target_value' => ['nullable', 'numeric', 'min:0'],
            'target_unit' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $data['is_active'] ?? false;
        $habit->update($data);

        return redirect()->route('habits.index')
            ->with('success', 'Habit updated successfully.');
    }

    public function destroy(Habit $habit)
    {
        abort_if($habit->user_id !== auth()->id(), 403);
        $habit->logs()->delete();
        $habit->delete();

        return redirect()->route('habits.index')
            ->with('success', 'Habit deleted successfully.');
    }

    public function log(Request $request, Habit $habit)
    {
        abort_if($habit->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'log_date' => ['nullable', 'date'],
            'completed' => ['nullable', 'boolean'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $data['log_date'] = $data['log_date'] ?? now()->toDateString();
        $data['completed'] = $data['completed'] ?? true;
        $data['user_id'] = $request->user()->id;

        HabitLog::updateOrCreate(
            ['habit_id' => $habit->id, 'log_date' => $data['log_date']],
            $data
        );

        return back()->with('success', 'Habit logged successfully.');
    }

    public function history(Request $request, Habit $habit)
    {
        abort_if($habit->user_id !== auth()->id(), 403);

        $logs = $habit->logs()->orderBy('log_date', 'desc')->paginate(30);
        $totalLogs = $habit->logs()->count();
        $streak = $this->calculateStreak($habit);

        $completedDays = $habit->logs()->where('completed', true)->count();
        $completionRate = $totalLogs > 0 ? round(($completedDays / $totalLogs) * 100) : 0;

        $stats = [
            'total_logs' => $totalLogs,
            'current_streak' => $streak,
            'completion_rate' => $completionRate,
        ];

        return view('student.habits.history', compact('habit', 'logs', 'stats'));
    }

    private function calculateStreak(Habit $habit): int
    {
        $streak = 0;
        $date = now()->toDateString();

        while (true) {
            $log = $habit->logs()->where('log_date', $date)->first();
            if ($log && $log->completed) {
                $streak++;
                $date = date('Y-m-d', strtotime($date . ' -1 day'));
            } else {
                break;
            }
        }

        return $streak;
    }
}
