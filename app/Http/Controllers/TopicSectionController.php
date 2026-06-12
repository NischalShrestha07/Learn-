<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TopicSection;
use Illuminate\Http\Request;

class TopicSectionController extends Controller
{
    public function store(Request $request, Topic $topic)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);

        $data = $request->validate([
            'type' => ['required', 'in:overview,notes,examples,resources,summary,custom'],
            'content' => ['required', 'string'],
            'custom_type' => ['nullable', 'string', 'max:100'],
        ]);

        $maxOrder = $topic->sections()->max('order') ?? 0;

        $topic->sections()->create([
            'type' => $data['type'] === 'custom' ? ($data['custom_type'] ?? 'custom') : $data['type'],
            'content' => $data['content'],
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Section added.');
    }

    public function update(Request $request, Topic $topic, TopicSection $section)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);

        $data = $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'content' => ['required', 'string'],
        ]);

        $section->update($data);

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Section updated.');
    }

    public function destroy(Request $request, Topic $topic, TopicSection $section)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);
        $section->delete();

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Section removed.');
    }

    public function reorder(Request $request, Topic $topic)
    {
        abort_if($topic->created_by !== $request->user()->id, 403);

        $data = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*.id' => ['required', 'exists:topic_sections,id'],
            'sections.*.order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data['sections'] as $item) {
            TopicSection::where('id', $item['id'])
                ->where('topic_id', $topic->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['ok' => true]);
    }
}
