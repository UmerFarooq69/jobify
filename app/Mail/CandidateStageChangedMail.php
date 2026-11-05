<?php
namespace App\Mail;

use App\Models\CandidateApplication;
use App\Models\PipelineAutomation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateStageChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CandidateApplication $application,
        public PipelineAutomation   $automation,
        public string               $stageName,
    ) {}

    public function envelope(): Envelope
    {
        $config  = $this->automation->action_config ?? [];
        $jobTitle = $this->application->job?->title ?? 'your application';

        $subject = !empty($config['subject'])
            ? str_replace(['{{job_title}}', '{{stage_name}}'], [$jobTitle, $this->stageName], $config['subject'])
            : match($this->automation->action_type) {
                'schedule_interview' => "Interview Invitation — {$jobTitle}",
                'request_assessment' => "Assessment Request — {$jobTitle}",
                default              => "Application Update — {$jobTitle}",
            };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.candidate-stage-changed');
    }

    public function attachments(): array { return []; }
}
