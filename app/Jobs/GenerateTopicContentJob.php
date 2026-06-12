<?php

namespace App\Jobs;

use App\Contracts\AiContentService;
use App\Models\Topic;
use App\Models\TopicSection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class GenerateTopicContentJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(public readonly int $topicId) {}

    public function handle(AiContentService $ai): void
    {
        $topic = Topic::findOrFail($this->topicId);

        $data = $ai->generateTopicContent($topic->title);

        $topic->update([
            'description'       => $data['description'] ?? null,
            'generation_status' => 'completed',
        ]);

        foreach ($data['sections'] as $index => $section) {
            TopicSection::create([
                'topic_id' => $topic->id,
                'type'     => $section['type'],
                'content'  => $section['content'],
                'order'    => $index,
            ]);
        }
    }

    public function failed(Throwable $e): void
    {
        Topic::where('id', $this->topicId)->update(['generation_status' => 'failed']);
    }
}
