<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmNotify extends Mailable
{
    use Queueable, SerializesModels;
    public $orderData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderData)
    {
        $this->order=$orderData;
        //dd($this->order);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  
        $subject = "Your Booking has been confirmed!";
        $emails_name = 'Training Block';
        $admin_email = "auto-reply@trainingblockusa.com";
        $admin_name = "Training Block";  
        return $this->view('email.booking-confirm')->from($admin_email, $admin_name)
        ->subject($subject)
        ->with(['order'=>$this->order]);
    }
}
