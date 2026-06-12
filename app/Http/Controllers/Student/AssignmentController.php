<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->assignments();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                    ->orWhere('course', 'like', "%{$q}%");
            });
        }

        $assignments = $query->orderBy('due_date')->orderBy('priority')->paginate(20);
        $stats = [
            'pending' => $request->user()->assignments()->where('status', 'pending')->count(),
            'submitted' => $request->user()->assignments()->where('status', 'submitted')->count(),
            'graded' => $request->user()->assignments()->where('status', 'graded')->count(),
            'overdue' => $request->user()->assignments()->where('status', '!=', 'graded')->whereDate('due_date', '<', now())->count(),
        ];

        return view('student.assignments.index', compact('assignments', 'stats'));
    }

    public function create()
    {
        return view('student.assignments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'course' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'priority' => ['required', 'in:low,medium,high'],
            'notes' => ['nullable', 'string'],
        ]);

        $request->user()->assignments()->create($data);

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        abort_if($assignment->user_id !== auth()->id(), 403);
        return view('student.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        abort_if($assignment->user_id !== auth()->id(), 403);
        return view('student.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        abort_if($assignment->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'course' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:pending,submitted,graded,returned'],
            'grade' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'max_grade' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'priority' => ['required', 'in:low,medium,high'],
            'notes' => ['nullable', 'string'],
        ]);

        $assignment->update($data);

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        abort_if($assignment->user_id !== auth()->id(), 403);
        $assignment->delete();

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }
}
