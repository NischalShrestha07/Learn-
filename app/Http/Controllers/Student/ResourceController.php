<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Topic;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->resources()->with('topic');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('url', 'like', "%{$q}%");
            });
        }

        if ($request->boolean('favourites')) {
            $query->where('is_favourite', true);
        }

        $resources = $query->latest()->paginate(15);
        $topics = Topic::where('created_by', $request->user()->id)
            ->orderBy('title')->get();

        return view('student.resources.index', compact('resources', 'topics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2000'],
            'type' => ['required', 'in:link,pdf,video,book,article,other'],
            'description' => ['nullable', 'string', 'max:2000'],
            'topic_id' => ['nullable', 'exists:topics,id'],
        ]);

        $request->user()->resources()->create($data);

        return redirect()->route('resources.index')
            ->with('success', 'Resource added successfully.');
    }

    public function update(Request $request, Resource $resource)
    {
        abort_if($resource->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2000'],
            'type' => ['required', 'in:link,pdf,video,book,article,other'],
            'description' => ['nullable', 'string', 'max:2000'],
            'topic_id' => ['nullable', 'exists:topics,id'],
        ]);

        $resource->update($data);

        return redirect()->route('resources.index')
            ->with('success', 'Resource updated successfully.');
    }

    public function toggleFavourite(Resource $resource)
    {
        abort_if($resource->user_id !== auth()->id(), 403);
        $resource->update(['is_favourite' => !$resource->is_favourite]);

        return back()->with('success', $resource->is_favourite ? 'Added to favourites.' : 'Removed from favourites.');
    }

    public function destroy(Resource $resource)
    {
        abort_if($resource->user_id !== auth()->id(), 403);
        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Resource deleted successfully.');
    }
}
