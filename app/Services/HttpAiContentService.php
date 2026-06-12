<?php

namespace App\Services;

use App\Contracts\AiContentService;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HttpAiContentService implements AiContentService
{
    public function generateTopicContent(string $title): array
    {
        $response = Http::withHeaders([
            'x-api-key'         => config('services.ai.key'),
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post(config('services.ai.endpoint'), [
            'model'      => config('services.ai.model'),
            'max_tokens' => 4096,
            'messages'   => [
                [
                    'role'    => 'user',
                    'content' => $this->buildPrompt($title),
                ],
            ],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException("AI API error: {$response->status()} — {$response->body()}");
        }

        $text = $response->json('content.0.text') ?? '';

        // Strip markdown code fences if present
        $json = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($text));

        $data = json_decode($json, true);

        if (! is_array($data) || ! isset($data['sections'])) {
            throw new RuntimeException('AI response missing expected structure');
        }

        return $data;
    }

    private function buildPrompt(string $title): string
    {
        return <<<PROMPT
        Generate structured educational content for the topic: "{$title}"

        Return ONLY valid JSON with this exact structure (no markdown, no explanation):
        {
          "description": "Brief 2-sentence overview of the topic",
          "sections": [
            {"type": "overview", "content": "High-level introduction in markdown"},
            {"type": "explanation", "content": "Detailed explanation with key concepts in markdown"},
            {"type": "examples", "content": "Practical examples in markdown"},
            {"type": "quiz", "content": "5 quiz questions with answers in markdown"},
            {"type": "summary", "content": "Key takeaways and next steps in markdown"}
          ]
        }
        PROMPT;
    }
}
