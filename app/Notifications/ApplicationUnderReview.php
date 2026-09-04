<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationUnderReview extends Notification
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

        return (new MailMessage)
            ->subject('Your MOSRAC Application Is Now Under Review')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('We are pleased to inform you that your application to the National Internship Portal of Armenia is now being actively reviewed by our admissions team.')
            ->line('**Program:** ' . ($program?->name ?? 'N/A'))
            ->line('**Degree Level:** ' . ucfirst($program?->degree_level ?? 'N/A'))
            ->action('View Your Application', url('/application/' . $this->application->id))
            ->line('You will receive another email once a final decision has been made. In the meantime, you can log in to the portal to check your application status at any time.')
            ->line('Thank you for your patience.')
            ->salutation('MOSRAC Admissions Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'under_review',
            'title'          => 'Application Under Review 🔍',
            'message'        => 'Your application for ' . ($this->application->program?->name ?? 'N/A') . ' is now being reviewed by our admissions team.',
            'application_id' => $this->application->id,
            'url'            => url('/application/' . $this->application->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
