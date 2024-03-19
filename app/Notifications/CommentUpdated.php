<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentUpdated extends Notification
{
    use Queueable;
    protected $comment;


    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }




    public function toDatabase(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'comment' => $this->comment->comment,
            'user_id' => $this->comment->user_id,
        ];
    }
}
