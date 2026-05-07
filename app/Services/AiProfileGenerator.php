<?php
namespace App\Services;

use App\Models\AiContentDraft;

class AiProfileGenerator {
    public function createDraftsForUser(int $userId, array $targets): void
    {
        foreach ($targets as $target) {
            $payload = $this->callAiFor($target); // your LLM call → returns array/string
            AiContentDraft::create([
                'user_id' => $userId,
                'target'  => $target,               // e.g. 'profile.about_short'
                'payload' => is_array($payload) ? $payload : ['text' => $payload],
                'status'  => 'draft',
                'source'  => 'ai',
            ]);
        }
    }

    protected function callAiFor(string $target) {
        // Compose prompt from existing DB data and return AI text
        return ['text' => 'Generated content ...'];
    }
}
