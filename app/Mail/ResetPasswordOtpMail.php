<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPasswordOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $user;

    public function __construct($otp, User $user)
    {
        $this->otp = $otp;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Reset Password OTP - BI Mengajar')
                    ->view('emails.reset_otp');
    }
}
