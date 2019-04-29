<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordVendor;

class ResetPassword extends ResetPasswordVendor
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Wachtwoord reset ".config('app.name'))
            ->line('U ontvangt deze email omdat wij een wachtwoord reset verzoek kregen voor uw account.')
            ->action('Reset wachtwoord', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('Als u dit verzoek niet heeft gedaan, dan hoeft u geen verdere actie te ondernemen.');
    }
}
