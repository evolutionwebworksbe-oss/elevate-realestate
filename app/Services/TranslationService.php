<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class TranslationService
{
    public function translateToEnglish(string $text): string
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional translator. Translate Dutch real estate descriptions to English. Keep the tone professional and preserve formatting. Only return the translation, nothing else.'
                ],
                [
                    'role' => 'user',
                    'content' => $text
                ]
            ],
            'temperature' => 0.3,
        ]);

        return $result->choices[0]->message->content;
    }
}