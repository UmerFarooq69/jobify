<?php
namespace App\Services;

use App\Models\CandidateProfile;

class SkillGapService
{
    public function __construct(protected LlmService $llm) {}

    public function analyse(CandidateProfile $candidate, string $targetRole): array
    {
        set_time_limit(120);

        $skills     = implode(', ', $candidate->skills ?? []);
        $experience = collect($candidate->experience ?? [])->map(fn($e) => ($e['title'] ?? '') . ' at ' . ($e['company'] ?? ''))->implode('; ');

        $result = $this->llm->chatJson([
            ['role' => 'system', 'content' => 'You are a career coach. Return JSON with exactly these keys: missing_skills (array of {skill, priority: "high"|"medium"|"low", resource: string course/book name}), existing_strengths (array of strings), roadmap (array of {step, description, duration}), overall_readiness (integer 0-100). Be concise.'],
            ['role' => 'user', 'content' => "Current skills: {$skills}\nExperience: {$experience}\nTarget role: {$targetRole}"],
        ], ['timeout' => 90]);

        return $result ?? ['missing_skills' => [], 'existing_strengths' => [], 'roadmap' => [], 'overall_readiness' => 0];
    }
}
