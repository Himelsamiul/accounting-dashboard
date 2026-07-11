<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;

class ProjectCodesMail extends Mailable
{
    use Queueable;

    /** @param Collection $projects Projects whose tracking codes to resend. */
    public function __construct(public Collection $projects, public ?string $clientName = null)
    {
    }

    public function build()
    {
        return $this->subject('Your Project Tracking Codes — Prime Byte')
            ->view('emails.project-codes');
    }
}
