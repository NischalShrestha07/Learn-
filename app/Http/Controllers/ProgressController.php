<?php

namespace App\Http\Controllers;

use App\Models\TopicProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $progress = TopicProgress::with('topic')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('last_studied_at')
            ->paginate(20);

        return view('progress.index', compact('progress'));
    }
}
