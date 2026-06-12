<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $semesters = $request->user()->grades()
            ->selectRaw('DISTINCT CONCAT(semester, " ", year) as label, semester, year')
            ->orderBy('year', 'desc')
            ->orderByRaw("FIELD(semester, 'Spring','Summer','Fall')")
            ->get();

        $activeSemester = $request->get('semester');
        $activeYear = $request->get('year');

        $query = $request->user()->grades();

        if ($activeSemester && $activeYear) {
            $query->where('semester', $activeSemester)->where('year', $activeYear);
        }

        $grades = $query->orderBy('year', 'desc')
            ->orderByRaw("FIELD(semester, 'Spring','Summer','Fall')")
            ->orderBy('course')
            ->paginate(20);

        // GPA calculation
        $allGrades = $request->user()->grades()->get();
        $totalPoints = 0;
        $totalCredits = 0;
        foreach ($allGrades as $g) {
            if ($g->grade_points !== null) {
                $totalPoints += $g->grade_points * $g->credits;
                $totalCredits += $g->credits;
            }
        }
        $cgpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : null;

        return view('student.grades.index', compact('grades', 'semesters', 'activeSemester', 'activeYear', 'cgpa', 'totalCredits'));
    }

    public function create()
    {
        return view('student.grades.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'numeric', 'min:0.5', 'max:25'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'letter_grade' => ['nullable', 'string', 'max:2'],
            'grade_points' => ['nullable', 'numeric', 'min:0', 'max:4.33'],
            'semester' => ['required', 'in:Spring,Summer,Fall'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'is_elective' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['is_elective'] = $data['is_elective'] ?? false;

        $request->user()->grades()->create($data);

        return redirect()->route('grades.index')
            ->with('success', 'Grade added successfully.');
    }

    public function edit(Grade $grade)
    {
        abort_if($grade->user_id !== auth()->id(), 403);
        return view('student.grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        abort_if($grade->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'course' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'numeric', 'min:0.5', 'max:25'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'letter_grade' => ['nullable', 'string', 'max:2'],
            'grade_points' => ['nullable', 'numeric', 'min:0', 'max:4.33'],
            'semester' => ['required', 'in:Spring,Summer,Fall'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'is_elective' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['is_elective'] = $data['is_elective'] ?? false;
        $grade->update($data);

        return redirect()->route('grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        abort_if($grade->user_id !== auth()->id(), 403);
        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', 'Grade deleted successfully.');
    }
}
