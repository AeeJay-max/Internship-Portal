<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationApproved extends Notification
{
    use Queueable;

    public function __construct(public Application $application) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $student        = $this->application->student;
        $program        = $this->application->program;
        $studentNumber  = $student?->student_number ?? 'N/A';
        $enrollmentDate = $student?->enrollment_date
            ? \Carbon\Carbon::parse($student->enrollment_date)->format('F d, Y')
            : now()->format('F d, Y');

        return (new MailMessage)
            ->subject('Congratulations — Your Application Has Been Approved')
            ->greeting('Congratulations, ' . $notifiable->name . '!')
            ->line('We are delighted to inform you that your application to the **MoSRAC Internship Portal** has been reviewed and approved.')
            ->line('**Program:** ' . ($program?->name ?? 'N/A'))
            ->line('**Degree Level:** ' . ucfirst($program?->degree_level ?? 'N/A'))
            ->line('**Student Number:** ' . $studentNumber)
            ->line('**Enrollment Date:** ' . $enrollmentDate)
            ->action('View Your Application', url('/application/' . $this->application->id))
            ->line('Please log in to the portal to view your full application details and student information.')
            ->line('Welcome to the MoSRAC family! We look forward to supporting you throughout your internship journey.')
            ->salutation('MoSRAC Portal Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'approved',
            'title'          => 'Application Approved 🎉',
            'message'        => 'Your application for ' . ($this->application->program?->name ?? 'N/A') . ' has been approved. Student number: ' . ($this->application->student?->student_number ?? 'N/A'),
            'application_id' => $this->application->id,
            'url'            => url('/application/' . $this->application->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
