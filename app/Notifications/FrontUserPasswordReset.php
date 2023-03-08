<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Auth\Notifications\ResetPassword; // add this.

class FrontUserPasswordReset extends ResetPassword // change extends from Notification to ResetPassword
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            //->view('front.emails.password')
            ->from('siteadmin@trainingblockusa.com', 'Training Block')
            ->subject('Reset Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url('password/reset', [$this->token, $notifiable->email], false))// add this. this is $actionUrl
            ->line('If you did not request a password reset, no further action is required.');
            
    }
}