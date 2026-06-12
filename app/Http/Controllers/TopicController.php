<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateTopicRequest;
use App\Http\Requests\SearchTopicRequest;
use App\Jobs\GenerateTopicContentJob;
use App\Models\LearningSession;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function search(SearchTopicRequest $request)
    {
        $query = $request->validated('q');

        $topics = Topic::where('generation_status', 'completed')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('topics.search', compact('topics', 'query'));
    }

    public function show(Request $request, string $slug)
    {
        $topic = Topic::where('slug', $slug)->firstOrFail();

        // Start a learning session when student opens a ready topic
        if (auth()->check() && $topic->isReady()) {
            LearningSession::create([
                'user_id'    => $request->user()->id,
                'topic_id'   => $topic->id,
                'started_at' => now(),
            ]);
        }

        $sections = $topic->sections;

        return view('topics.show', compact('topic', 'sections'));
    }

    public function status(string $slug)
    {
        $topic = Topic::where('slug', $slug)->firstOrFail();

        return response()->json([
            'ready'  => $topic->isReady(),
            'failed' => $topic->isFailed(),
        ]);
    }

    public function generate(GenerateTopicRequest $request)
    {
        $title = $request->validated('title');
        $slug  = Topic::generateSlug($title);

        $topic = Topic::create([
            'slug'              => $slug,
            'title'             => $title,
            'generation_status' => 'pending',
            'created_by'        => $request->user()->id,
        ]);

        GenerateTopicContentJob::dispatch($topic->id);

        return redirect()->route('topics.show', $topic->slug);
    }
}
