<?php
namespace App\Services;

use App\Models\CandidateProfile;
use App\Models\JobRequisition;

class ResumeGeneratorService
{
    public function __construct(protected LlmService $llm) {}

    public function generate(CandidateProfile $candidate, ?JobRequisition $job = null): array
    {
        $jobContext = $job ? "Target job: {$job->title} at {$job->organization->name}\n{$job->description}" : '';

        set_time_limit(120);
        $result = $this->llm->chatJson([
            ['role' => 'system', 'content' => 'You are an ATS-optimized resume writer. Generate a professional resume and return JSON with: headline, summary, skills (array), experience (array of {title,company,start_date,end_date,bullets: array}), education (array), certifications (array).'],
            ['role' => 'user', 'content' => "Candidate data: " . json_encode([
                'name'         => $candidate->user->name ?? '',
                'headline'     => $candidate->headline,
                'summary'      => $candidate->summary,
                'skills'       => $candidate->skills ?? [],
                'experience'   => $candidate->experience ?? [],
                'education'    => $candidate->education ?? [],
                'certifications' => $candidate->certifications ?? [],
            ]) . "\n\n{$jobContext}"],
        ]);

        return $result ?? [];
    }
}
