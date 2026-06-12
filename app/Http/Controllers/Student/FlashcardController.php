<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\FlashcardDeck;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FlashcardController extends Controller
{
    public function index(Request $request)
    {
        $decks = $request->user()->flashcardDecks()
            ->withCount('flashcards')
            ->with(['flashcards' => function ($q) {
                $q->withCount(['reviews as due_reviews' => function ($rq) {
                    $rq->where('next_review_at', '<=', Carbon::now());
                }]);
            }])
            ->latest()
            ->paginate(12);

        return view('student.flashcards.index', compact('decks'));
    }

    public function create()
    {
        $topics = Topic::where('created_by', request()->user()->id)
            ->orderBy('title')->get();

        return view('student.flashcards.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'topic_id' => ['nullable', 'exists:topics,id'],
        ]);

        $deck = $request->user()->flashcardDecks()->create($data);

        return redirect()->route('flashcards.show', $deck)
            ->with('success', 'Deck created. Now add some cards.');
    }

    public function show(FlashcardDeck $deck)
    {
        abort_if($deck->user_id !== auth()->id(), 403);

        $deck->load(['flashcards' => function ($q) {
            $q->withCount(['reviews as total_reviews']);
        }, 'topic']);

        $dueCards = $deck->flashcards()
            ->whereDoesntHave('reviews', function ($q) {
                $q->where('next_review_at', '>', Carbon::now());
            })
            ->count();

        return view('student.flashcards.show', compact('deck', 'dueCards'));
    }

    public function update(Request $request, FlashcardDeck $deck)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $deck->update($data);

        return redirect()->route('flashcards.show', $deck)
            ->with('success', 'Deck updated successfully.');
    }

    public function destroy(FlashcardDeck $deck)
    {
        abort_if($deck->user_id !== auth()->id(), 403);
        $deck->delete();

        return redirect()->route('flashcards.index')
            ->with('success', 'Deck deleted successfully.');
    }

    public function storeCard(Request $request, FlashcardDeck $deck)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'front' => ['required', 'string'],
            'back' => ['required', 'string'],
            'hint' => ['nullable', 'string', 'max:500'],
        ]);

        $deck->flashcards()->create($data);

        return redirect()->route('flashcards.show', $deck)
            ->with('success', 'Card added successfully.');
    }

    public function updateCard(Request $request, FlashcardDeck $deck, Flashcard $card)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'front' => ['required', 'string'],
            'back' => ['required', 'string'],
            'hint' => ['nullable', 'string', 'max:500'],
        ]);

        $card->update($data);

        return redirect()->route('flashcards.show', $deck)
            ->with('success', 'Card updated successfully.');
    }

    public function destroyCard(FlashcardDeck $deck, Flashcard $card)
    {
        abort_if($deck->user_id !== auth()->id(), 403);
        $card->delete();

        return redirect()->route('flashcards.show', $deck)
            ->with('success', 'Card deleted successfully.');
    }

    public function review(FlashcardDeck $deck)
    {
        abort_if($deck->user_id !== auth()->id(), 403);

        $cards = $deck->flashcards()
            ->withCount(['reviews as total_reviews'])
            ->where(function ($q) {
                $q->whereDoesntHave('reviews')
                    ->orWhereHas('reviews', function ($rq) {
                        $rq->where('next_review_at', '<=', Carbon::now());
                    });
            })
            ->with(['reviews' => function ($q) {
                $q->latest('reviewed_at')->limit(1);
            }])
            ->get();

        return view('student.flashcards.review', compact('deck', 'cards'));
    }

    public function submitReview(Request $request, FlashcardDeck $deck, Flashcard $card)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'quality' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $quality = (int) $data['quality'];

        $minutes = match ($quality) {
            5 => 1440 * 7,
            4 => 1440 * 3,
            3 => 1440,
            2 => 120,
            1 => 10,
            default => 1,
        };

        $card->reviews()->create([
            'user_id' => $request->user()->id,
            'quality' => $quality,
            'reviewed_at' => Carbon::now(),
            'next_review_at' => Carbon::now()->addMinutes($minutes),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('flashcards.review', $deck)
            ->with('success', 'Review saved.');
    }
}
