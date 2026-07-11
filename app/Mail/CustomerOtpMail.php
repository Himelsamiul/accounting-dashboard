<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Customer $customer, public string $otp)
    {
    }

    public function build()
    {
        return $this->subject('Your Verification Code — ' . $this->otp)
            ->view('emails.customer-otp');
    }
}
