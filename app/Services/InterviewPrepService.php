<?php
namespace App\Services;

use App\Models\CandidateProfile;
use App\Models\JobRequisition;

class InterviewPrepService
{
    public function __construct(protected LlmService $llm) {}

    public function generate(CandidateProfile $candidate, JobRequisition $job): array
    {
        set_time_limit(120);
        $result = $this->llm->chatJson([
            ['role' => 'system', 'content' => 'You are an interview coach. Generate interview prep material and return JSON with: categories (array of {name, questions: array of {question, tip, sample_answer}}).'],
            ['role' => 'user', 'content' => "Job: {$job->title}\nDescription: {$job->description}\nCandidate skills: " . implode(', ', $candidate->skills ?? [])],
        ]);

        return $result ?? ['categories' => []];
    }
}
