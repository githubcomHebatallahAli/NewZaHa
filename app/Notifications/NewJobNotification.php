<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewJobNotification extends Notification
{
    use Queueable;
    public $job;
    /**
     * Create a new notification instance.
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }



    public function toDatabase(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'address' => $this->job->address,
            'phoneNumber' => $this->job->phoneNumber,
            'qualification' => $this->job->qualification,
            'job' => $this->job->job,
            'yearsOfExperience' => $this->job->yearsOfExperience,
            'skills' => $this->job->skills,
            'user_id' => $this->job->user_id,
        ];
    }
}

