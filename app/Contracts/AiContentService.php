<?php

namespace App\Contracts;

interface AiContentService
{
    /**
     * Generate structured educational content for a topic title.
     *
     * Returns array with keys: description (string), sections (array of {type, content}).
     */
    public function generateTopicContent(string $title): array;
}
