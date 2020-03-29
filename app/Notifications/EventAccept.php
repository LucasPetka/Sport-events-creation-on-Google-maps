<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Event;
use App\User;
use Auth;

class EventAccept extends Notification
{
    use Queueable;

    public $event;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database','broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'notification_object' => $this->event,
            'type' => "eventAcc"
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/home');
        $user = User::find($this->event->person_id);

        return (new MailMessage)
            ->subject('Event has been accepted !')
            ->greeting('Hey, '. $user->name.' !')
            ->line('Your submited event "'. $this->event->title .'"  has been accepted!')
            ->action('Open profile', $url)
            ->line('Thank you for using our application!');
    }


    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'notification_object' => $this->event,
                'type' => "eventAcc"
            ]
        ];
    }


}
