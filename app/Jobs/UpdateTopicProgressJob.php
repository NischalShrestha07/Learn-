<?php

namespace App\Jobs;

use App\Models\SectionView;
use App\Models\Topic;
use App\Models\TopicProgress;
use App\Models\TopicSection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class UpdateTopicProgressJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $userId,
        public readonly int $topicId,
    ) {}

    public function handle(): void
    {
        $totalSections = TopicSection::where('topic_id', $this->topicId)->count();

        if ($totalSections === 0) {
            return;
        }

        $viewedSections = SectionView::where('user_id', $this->userId)
            ->whereIn('topic_section_id', TopicSection::where('topic_id', $this->topicId)->pluck('id'))
            ->count();

        $percentage = (int) round(($viewedSections / $totalSections) * 100);

        $status = match (true) {
            $percentage === 0   => 'not_started',
            $percentage === 100 => 'completed',
            default             => 'in_progress',
        };

        TopicProgress::updateOrCreate(
            ['user_id' => $this->userId, 'topic_id' => $this->topicId],
            [
                'status'                => $status,
                'completion_percentage' => $percentage,
                'last_studied_at'       => now(),
            ]
        );
    }
}
