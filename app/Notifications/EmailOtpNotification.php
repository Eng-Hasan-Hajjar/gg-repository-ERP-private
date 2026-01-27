<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailOtpNotification extends Notification // implements ShouldQueue (اختياري)
{
    use Queueable;

    public function __construct(
        public string $code,
        public int $minutes = 10
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Login Verification Code')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('We received a login attempt to your account.')
            ->line('Your verification code is:')
            ->line('**' . $this->code . '**')
            ->line("This code expires in {$this->minutes} minutes.")
            ->line('If you did not try to log in, please change your password immediately.');
    }
}
