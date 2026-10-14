<?php

namespace App\Notifications;

use App\Models\Application;
use App\Models\InternshipPlacement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SupervisorAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public InternshipPlacement $placement,
        public Application $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $personal = $this->application->personalInfo;
        $academic = $this->application->academicInfo;
        $dept     = $this->placement->department;

        $studentName = $personal
            ? trim(($personal->first_name ?? '') . ' ' . ($personal->middle_name ?? '') . ' ' . ($personal->last_name ?? ''))
            : ($this->application->user?->name ?? 'Student Intern');

        $studentEmail = $this->application->user?->email ?? 'N/A';
        $studentPhone = $personal?->phone ?? 'N/A';
        $institution  = $academic?->school_name ?? 'N/A';
        $program      = $academic?->program_of_study ?? 'N/A';
        $faculty      = $academic?->field_of_study ?? 'N/A';
        $academicLevel= $academic?->current_year_level ?? 'N/A';

        $startDate = $this->placement->start_date ? \Carbon\Carbon::parse($this->placement->start_date)->format('l, j F Y') : 'N/A';
        $endDate   = $this->placement->end_date ? \Carbon\Carbon::parse($this->placement->end_date)->format('l, j F Y') : 'N/A';
        $refNo     = $this->application->reference_number ?: ('MoSRAC-' . $this->application->id);

        return (new MailMessage)
            ->subject('Internship Student Supervisor Assignment: ' . $studentName . ' — MoSRAC')
            ->greeting('Dear ' . ($this->placement->supervisor_name ?: 'Supervisor') . ',')
            ->line('You have been officially selected and assigned as the workplace supervisor for a student on internship attachment with the Ministry of Sport, Recreation, Arts and Culture.')
            ->line('Please review the student details, start date, and end date below:')
            ->line('---')
            ->line('**STUDENT PARTICULARS:**')
            ->line('• **Student Full Name:** ' . $studentName)
            ->line('• **Application Reference:** ' . $refNo)
            ->line('• **Student Email:** ' . $studentEmail)
            ->line('• **Student Phone:** ' . $studentPhone)
            ->line('• **Tertiary Institution:** ' . $institution)
            ->line('• **Programme of Study:** ' . $program)
            ->line('• **Faculty:** ' . $faculty)
            ->line('• **Current Academic Level:** ' . $academicLevel)
            ->line('---')
            ->line('**ATTACHMENT & PLACEMENT DETAILS:**')
            ->line('• **Assigned Department:** ' . ($dept?->name ?? 'Ministry Department'))
            ->line('• **Division / Unit:** ' . ($this->placement->division_unit ?? 'General Administration'))
            ->line('• **Placement Location:** ' . ($this->placement->placement_location ?? 'Harare HQ'))
            ->line('• **Internship Start Date:** ' . $startDate)
            ->line('• **Internship End Date:** ' . $endDate)
            ->line('• **First Day Reporting Instructions:** ' . $this->placement->reporting_instructions)
            ->line('---')
            ->line('Please prepare to receive, orient, and supervise the student throughout their internship period.')
            ->salutation("Ministry of Sport, Recreation, Arts and Culture\nGovernment of Zimbabwe");
    }
}
