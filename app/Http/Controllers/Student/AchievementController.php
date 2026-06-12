<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->achievements()->orderBy('date', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $achievements = $query->paginate(20);
        $stats = [
            'total' => $request->user()->achievements()->count(),
            'certificates' => $request->user()->achievements()->where('type', 'certificate')->count(),
            'awards' => $request->user()->achievements()->where('type', 'award')->count(),
            'skills' => $request->user()->achievements()->where('type', 'skill')->count(),
        ];

        return view('student.achievements.index', compact('achievements', 'stats'));
    }

    public function create()
    {
        return view('student.achievements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:certificate,award,publication,skill,leadership,other'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048'],
        ]);

        $request->user()->achievements()->create($data);

        return redirect()->route('achievements.index')
            ->with('success', 'Achievement added successfully.');
    }

    public function edit(Achievement $achievement)
    {
        abort_if($achievement->user_id !== auth()->id(), 403);
        return view('student.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        abort_if($achievement->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:certificate,award,publication,skill,leadership,other'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048'],
        ]);

        $achievement->update($data);

        return redirect()->route('achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    public function destroy(Achievement $achievement)
    {
        abort_if($achievement->user_id !== auth()->id(), 403);
        $achievement->delete();

        return redirect()->route('achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }
}
