<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->exams();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('exam_type', $request->type);
        }

        $query->orderBy('exam_date');

        $upcoming = $request->user()->exams()
            ->where('status', 'upcoming')
            ->whereDate('exam_date', '>=', now()->subDay())
            ->orderBy('exam_date')
            ->get();

        $exams = $query->paginate(20);

        return view('student.exams.index', compact('exams', 'upcoming'));
    }

    public function create()
    {
        return view('student.exams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'exam_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'location' => ['nullable', 'string', 'max:255'],
            'exam_type' => ['required', 'in:quiz,midterm,final,presentation,other'],
            'notes' => ['nullable', 'string'],
        ]);

        $request->user()->exams()->create($data);

        return redirect()->route('exams.index')
            ->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        abort_if($exam->user_id !== auth()->id(), 403);
        return view('student.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        abort_if($exam->user_id !== auth()->id(), 403);
        return view('student.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        abort_if($exam->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'exam_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'location' => ['nullable', 'string', 'max:255'],
            'exam_type' => ['required', 'in:quiz,midterm,final,presentation,other'],
            'status' => ['required', 'in:upcoming,taken,cancelled'],
            'grade' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'max_grade' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'notes' => ['nullable', 'string'],
        ]);

        $exam->update($data);

        return redirect()->route('exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        abort_if($exam->user_id !== auth()->id(), 403);
        $exam->delete();

        return redirect()->route('exams.index')
            ->with('success', 'Exam deleted successfully.');
    }
}
