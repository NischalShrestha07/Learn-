<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\NoteTag;
use App\Models\Topic;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->notes()->with('tags', 'topic');

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('note_tags.id', $request->tag));
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        $notes = $query->latest()->paginate(15);
        $tags = $request->user()->noteTags()->withCount('notes')->orderBy('name')->get();

        return view('student.notes.index', compact('notes', 'tags'));
    }

    public function create()
    {
        $topics = Topic::where('created_by', request()->user()->id)
            ->orderBy('title')->get();

        $tags = request()->user()->noteTags()->orderBy('name')->get();

        return view('student.notes.create', compact('topics', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'is_public' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:note_tags,id'],
        ]);

        $note = $request->user()->notes()->create([
            'title' => $data['title'],
            'content' => $data['content'],
            'topic_id' => $data['topic_id'] ?? null,
            'is_public' => $data['is_public'] ?? false,
        ]);

        if (!empty($data['tags'])) {
            $note->tags()->sync($data['tags']);
        }

        return redirect()->route('notes.show', $note)
            ->with('success', 'Note created successfully.');
    }

    public function show(Note $note)
    {
        abort_if($note->user_id !== auth()->id(), 403);
        $note->load('tags', 'topic');

        return view('student.notes.show', compact('note'));
    }

    public function edit(Request $request, Note $note)
    {
        abort_if($note->user_id !== $request->user()->id, 403);

        $topics = Topic::where('created_by', $request->user()->id)
            ->orderBy('title')->get();

        $tags = $request->user()->noteTags()->orderBy('name')->get();

        return view('student.notes.edit', compact('note', 'topics', 'tags'));
    }

    public function update(Request $request, Note $note)
    {
        abort_if($note->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'is_public' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:note_tags,id'],
        ]);

        $note->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'topic_id' => $data['topic_id'] ?? null,
            'is_public' => $data['is_public'] ?? false,
        ]);

        if (isset($data['tags'])) {
            $note->tags()->sync($data['tags']);
        }

        return redirect()->route('notes.show', $note)
            ->with('success', 'Note updated successfully.');
    }

    public function destroy(Note $note)
    {
        abort_if($note->user_id !== auth()->id(), 403);
        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', 'Note deleted successfully.');
    }

    public function tags(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        $request->user()->noteTags()->create($data);

        return back()->with('success', 'Tag created successfully.');
    }

    public function destroyTag(NoteTag $tag)
    {
        abort_if($tag->user_id !== auth()->id(), 403);
        $tag->delete();

        return back()->with('success', 'Tag deleted successfully.');
    }
}
