<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
        public string $statusTitle,
        public ?string $customNotes = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $applicantName = $this->application->personalInfo
            ? ($this->application->personalInfo->first_name . ' ' . $this->application->personalInfo->last_name)
            : $notifiable->name;

        $refNo = $this->application->reference_number ?: ('APP-' . $this->application->id);
        $portalUrl = url('/application/' . $this->application->id);

        $mail = (new MailMessage)
            ->subject('Urgent: Your Application Status Has Been Updated — MoSRAC Internship Portal')
            ->greeting('Dear ' . $applicantName . ',')
            ->line('The status of your internship application (**' . $refNo . '**) has been updated by Ministry administrators.')
            ->line('**New Application Status:** ' . $this->statusTitle);

        if ($this->application->isInterviewRequired() && $this->application->interview_date) {
            $dateStr = \Carbon\Carbon::parse($this->application->interview_date)->format('l, j F Y');
            $mail->line('**Interview Date:** ' . $dateStr)
                 ->line('**Interview Time:** ' . ($this->application->interview_time ?? 'As notified'))
                 ->line('**Venue / Location:** ' . ($this->application->interview_location ?? 'Ministry HQ'));
        }

        if ($this->customNotes) {
            $mail->line('**Ministry Remarks / Instructions:**')
                 ->line($this->customNotes);
        }

        $mail->action('Check Portal Immediately', $portalUrl)
             ->line('Please log in to the MoSRAC portal with immediate effect to review your updated status and complete any pending requirements.')
             ->salutation("Ministry of Sport, Recreation, Arts and Culture\nGovernment of Zimbabwe");

        return $mail;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'status_updated',
            'title'          => 'Application Status Updated: ' . $this->statusTitle,
            'message'        => 'Your application status has been updated to ' . $this->statusTitle . '. Please check the portal immediately.',
            'application_id' => $this->application->id,
            'url'            => url('/application/' . $this->application->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
