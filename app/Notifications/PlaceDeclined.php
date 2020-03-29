<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\DeclinedPlaces;
use App\AcceptedPlaces;
use App\User;
use Auth;

class PlaceDeclined extends Notification
{
    use Queueable;

    public $place;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(DeclinedPlaces $place)
    {
        $this->place = $place;
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
            'notification_object' => $this->place,
            'type' => "placeDec"
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
        $user = User::find($this->place->personid);

        return (new MailMessage)
            ->subject('Place has been declined !')
            ->greeting('Hey, '. $user->name.' !')
            ->line('Your place "'. $this->place->title .'" has been declined!')
            ->action('Open profile', $url)
            ->line('Thank you for using our application!');
    }


    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'notification_object' => $this->place,
                'type' => "placeDec"
            ]
        ];
    }


}
