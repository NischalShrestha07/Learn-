<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->todos();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status') && $request->status === 'completed') {
            $query->where('is_completed', true);
        } elseif ($request->filled('status') && $request->status === 'pending') {
            $query->where('is_completed', false);
        }

        $todos = $query->orderBy('is_completed')->orderBy('due_date')->orderBy('priority')->paginate(25);
        $categories = $request->user()->todos()->selectRaw('DISTINCT category')->whereNotNull('category')->pluck('category');

        return view('student.todos.index', compact('todos', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $request->user()->todos()->create($data);

        return back()->with('success', 'Task added successfully.');
    }

    public function update(Request $request, Todo $todo)
    {
        abort_if($todo->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $todo->update($data);

        return back()->with('success', 'Task updated successfully.');
    }

    public function toggle(Todo $todo)
    {
        abort_if($todo->user_id !== auth()->id(), 403);

        $todo->update([
            'is_completed' => !$todo->is_completed,
            'completed_at' => $todo->is_completed ? null : now(),
        ]);

        return back();
    }

    public function destroy(Todo $todo)
    {
        abort_if($todo->user_id !== auth()->id(), 403);
        $todo->delete();

        return back()->with('success', 'Task deleted successfully.');
    }
}
