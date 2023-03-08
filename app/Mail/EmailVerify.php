<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;
    public $frontUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($frontUser)
    {
        $this->user=$frontUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Verify your email address";
        $emails_name = 'Training Block';
        $admin_email = "auto-reply@trainingblockusa.com";
        $admin_name = "Training Block";  
        return $this->view('email.verify-email')->from($admin_email, $admin_name)
        ->subject($subject)
        ->with(['user'=>$this->user]);
    }
}
