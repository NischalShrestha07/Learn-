<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $topics = $request->user()->topics()
            ->withCount('sections', 'learningSessions')
            ->latest()
            ->paginate(12);

        return view('topics.index', compact('topics'));
    }

    public function create()
    {
        return view('topics.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $topic = $request->user()->topics()->create([
            'slug' => Topic::generateSlug($data['title']),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Topic created successfully.');
    }

    public function show(Request $request, Topic $topic)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);

        // Start learning session
        LearningSession::create([
            'user_id' => $request->user()->id,
            'topic_id' => $topic->id,
            'started_at' => now(),
        ]);

        $topic->load('sections');

        return view('topics.show', compact('topic'));
    }

    public function edit(Request $request, Topic $topic)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);

        return view('topics.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:active,archived'],
        ]);

        $topic->update($data);

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Topic updated successfully.');
    }

    public function destroy(Request $request, Topic $topic)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);
        $topic->delete();

        return redirect()->route('topics.index')
            ->with('success', 'Topic deleted successfully.');
    }
}
