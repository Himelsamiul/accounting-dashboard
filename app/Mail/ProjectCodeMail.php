<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project)
    {
    }

    public function build()
    {
        return $this->subject('Your Project Tracking Code — ' . $this->project->name)
            ->view('emails.project-code');
    }
}
