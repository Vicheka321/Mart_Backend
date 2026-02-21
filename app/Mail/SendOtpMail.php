<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Message;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your OTP Code')
                    ->text('emails.raw')  // Use plain text view
                    ->with(['otp' => $this->otp]);
    }
}
