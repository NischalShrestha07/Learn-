<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateTopicProgressJob;
use App\Models\LearningSession;
use App\Models\Topic;
use Illuminate\Http\Request;

class LearningSessionController extends Controller
{
    public function end(Request $request, string $slug)
    {
        $topic = Topic::where('slug', $slug)->firstOrFail();

        $session = LearningSession::where('user_id', $request->user()->id)
            ->where('topic_id', $topic->id)
            ->whereNull('ended_at')
            ->latest('started_at')
            ->first();

        if ($session) {
            $session->update([
                'ended_at'        => now(),
                'duration_seconds' => now()->diffInSeconds($session->started_at),
            ]);

            UpdateTopicProgressJob::dispatch($request->user()->id, $topic->id);
        }

        return response()->json(['ok' => true]);
    }
}
