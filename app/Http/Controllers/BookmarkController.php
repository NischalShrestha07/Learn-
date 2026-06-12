<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Topic;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle(Request $request, Topic $topic)
    {
        $existing = Bookmark::where('user_id', $request->user()->id)
            ->where('topic_id', $topic->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            Bookmark::create([
                'user_id'  => $request->user()->id,
                'topic_id' => $topic->id,
            ]);
            $bookmarked = true;
        }

        return response()->json(['bookmarked' => $bookmarked]);
    }
}
