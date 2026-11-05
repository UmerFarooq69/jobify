<?php
namespace App\Mail;

use App\Models\CandidateApplication;
use App\Models\PipelineAutomation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamStageNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CandidateApplication $application,
        public PipelineAutomation   $automation,
        public string               $stageName,
    ) {}

    public function envelope(): Envelope
    {
        $jobTitle = $this->application->job?->title ?? 'a job';
        return new Envelope(subject: "Pipeline Update: {$this->stageName} — {$jobTitle}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.team-stage-notification');
    }

    public function attachments(): array { return []; }
}
