<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to MoSRAC Internship Portal — Verify Your Email')
            ->greeting('Welcome to MoSRAC!')
            ->line('Thank you for registering with the Ministry of Sport, Recreation, Arts & Culture Internship Portal.')
            ->line('You are one step away from starting your application. Please verify your email address by clicking the button below.')
            ->action('Verify Email Address', $url)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('MoSRAC Portal Team');
    }
}
