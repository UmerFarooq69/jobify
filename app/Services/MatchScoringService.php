<?php
namespace App\Services;

use App\Models\CandidateProfile;
use App\Models\JobRequisition;

class MatchScoringService
{
    public function __construct(protected LlmService $llm) {}

    public function score(CandidateProfile $candidate, JobRequisition $job): array
    {
        $candidateData = [
            'headline'       => $candidate->headline,
            'summary'        => $candidate->summary,
            'skills'         => $candidate->skills ?? [],
            'experience'     => $candidate->experience ?? [],
            'education'      => $candidate->education ?? [],
        ];

        $jobData = [
            'title'          => $job->title,
            'description'    => $job->description,
            'requirements'   => $job->requirements,
            'skills'         => $job->skills ?? [],
            'employment_type'=> $job->employment_type,
            'location'       => $job->location,
        ];

        set_time_limit(120);
        $result = $this->llm->chatJson([
            ['role' => 'system', 'content' => 'You are a hiring AI. Score the candidate against the job and return JSON with: score (0-100 integer), strengths (array of strings), missing_skills (array of strings), interview_questions (array of 5 strings), readiness_score (0-100 integer), summary (string).'],
            ['role' => 'user', 'content' => "Candidate:\n" . json_encode($candidateData) . "\n\nJob:\n" . json_encode($jobData)],
        ], ['timeout' => 90]);

        return $result ?? ['score' => 0, 'strengths' => [], 'missing_skills' => [], 'interview_questions' => [], 'readiness_score' => 0, 'summary' => ''];
    }
}
