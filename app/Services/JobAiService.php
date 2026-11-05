<?php
namespace App\Services;

class JobAiService
{
    public function __construct(protected LlmService $llm) {}

    public function draftJob(string $prompt, string $orgName = ''): array
    {
        set_time_limit(120);
        $result = $this->llm->chatJson([
            ['role' => 'system', 'content' => 'You are an HR specialist. Create a complete job posting from the prompt and return JSON with: title (string), summary (plain text, 2 sentences), description (plain text with sections separated by newlines, no HTML tags), requirements (plain text bullet points starting with - , no HTML tags), skills (array of strings), salary_min (integer), salary_max (integer), currency (string default QAR), employment_type (full_time/part_time/contract/freelance/internship), location (string). Do NOT use any HTML tags anywhere.'],
            ['role' => 'user', 'content' => "Company: {$orgName}\nPrompt: {$prompt}"],
        ]);

        return $result ?? [];
    }

    public function rankCandidates(array $candidates, array $job): array
    {
        $result = $this->llm->chatJson([
            ['role' => 'system', 'content' => 'You are a recruiting AI. Rank the candidates for this job and return JSON with: rankings (array of {candidate_id, score: 0-100, reasoning: string}) sorted by score descending.'],
            ['role' => 'user', 'content' => "Job: " . json_encode($job) . "\n\nCandidates: " . json_encode($candidates)],
        ]);

        return $result['rankings'] ?? [];
    }
}
