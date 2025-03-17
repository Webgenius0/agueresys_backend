<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    // To send notification to the database
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // To store the notification in the database
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->data['message'],
            'url' => $this->data['url'],
        ];
    }

    // To broadcast the notification
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->data['message'],
            'url' => $this->data['url'],
        ]);
    }
}
