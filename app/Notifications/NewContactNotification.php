<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewContactNotification extends Notification
{
    use Queueable;
    public $contact;
    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'contact_id' => $this->contact->id,
            'phoneNumber' => $this->contact->phoneNumber,
            'message' => $this->contact->message,
            'user_id' => $this->contact->user_id,

        ];
    }


}
