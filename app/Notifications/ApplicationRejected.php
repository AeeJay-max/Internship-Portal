<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationRejected extends Notification
{
    use Queueable;

    public function __construct(public Application $application) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $program = $this->application->program;
        $reason  = $this->application->review_notes;

        return (new MailMessage)
            ->subject('Update on Your MOSRAC Application')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Thank you for your interest in studying at the National Internship Portal of Armenia and for the time you invested in your application.')
            ->line('After careful review, we regret to inform you that your application for the following program has not been successful at this time:')
            ->line('**Program:** ' . ($program?->name ?? 'N/A'))
            ->line('**Degree Level:** ' . ucfirst($program?->degree_level ?? 'N/A'))
            ->when($reason, function (MailMessage $mail) use ($reason) {
                return $mail->line('**Reason:** ' . $reason);
            })
            ->action('View Your Application', url('/application/' . $this->application->id))
            ->line('We encourage you to review the feedback provided and consider reapplying in a future intake. If you have any questions, please do not hesitate to contact our admissions office.')
            ->salutation('MOSRAC Admissions Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'rejected',
            'title'          => 'Application Not Approved',
            'message'        => 'Your application for ' . ($this->application->program?->name ?? 'N/A') . ' was not approved.' .
                ($this->application->review_notes ? ' Reason: ' . $this->application->review_notes : ''),
            'application_id' => $this->application->id,
            'url'            => url('/application/' . $this->application->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
