<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentsRequested extends Notification
{
    use Queueable;

    public function __construct(public Application $application) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $program     = $this->application->program;
        $notes       = $this->application->review_notes;
        $flaggedIds  = $this->application->flagged_document_ids ?? [];
        $docNames    = \App\Models\DocumentType::whereIn('id', $flaggedIds)
            ->pluck('name')
            ->toArray();

        $mail = (new MailMessage)
            ->subject('Action Required — Document Re-upload Requested')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Our admissions team has reviewed your application for **' . ($program?->name ?? 'N/A') . '** and requires you to re-upload the following document(s):');

        foreach ($docNames as $docName) {
            $mail->line('• ' . $docName);
        }

        if ($notes) {
            $mail->line('**Admin Note:** ' . $notes);
        }

        return $mail
            ->action('Upload Documents Now', url('/application/' . $this->application->id))
            ->line('Please log in to the portal and upload the correct versions of the requested documents as soon as possible to avoid delays in processing your application.')
            ->line('Once you have re-uploaded your documents, our team will continue reviewing your application.')
            ->salutation('MoSRAC Portal Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'documents_requested',
            'title'          => 'Documents Required ⚠️',
            'message'        => 'Your application for ' . ($this->application->program?->name ?? 'N/A') . ' requires additional documents. Please upload them to continue.',
            'application_id' => $this->application->id,
            'url'            => url('/application/' . $this->application->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
